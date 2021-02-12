import {Component, OnInit} from '@angular/core';
import {ProductService} from '../../Services/product.service';
import {Article} from '../../model/Article';
import {Categorie} from '../../model/Categorie';
import {AuthenticationService} from '../../Services/authentication.service';
import {User} from '../../model/User';
import {url} from 'inspector';
import {Router} from '@angular/router';
import {Model} from '../../model/Model';
import {nodeDebugInfo} from '@angular/compiler-cli/src/ngtsc/util/src/typescript';
import pdfMake from 'pdfmake/build/pdfmake';

@Component({
  selector: 'app-article',
  templateUrl: './article.component.html',
  styleUrls: ['./article.component.css']
})
export class ArticleComponent implements OnInit {
  showLoadingIndicator = false;
  articles;
  categories;
  article = new Article();
  searchValue;
  page: number = 1;

  constructor(
    public authService: AuthenticationService,
    private productService: ProductService,
    private router: Router
  ) {
    this.article.categorie = new Categorie();
  }

  async ngOnInit() {
    this.showLoadingIndicator = true;

    if (this.categories == null) {
      await this.productService.categories.then(data => {
        this.categories = data['hydra:member'];
      }, error => {
        console.log('Error: ', error);
      });
    }

    if (this.articles == null) {
      await this.productService.articles.then(data => {
        this.articles = data['hydra:member'];
      }, error => {
        console.log('Error: ', error);
      });
    }

    this.showLoadingIndicator = false;

    console.log(this.articles);

  }


  addArticle() {
    this.productService.addArticle(this.article, this.authService.user)
      .subscribe(res => {
        this.articles.push(res);
        // @ts-ignore
        window.$('#myModalArticle').modal('toggle');
        console.log(this.articles);
        this.article = new Article();
      }, error => {
        console.log(error);
      });

  }

  confirmationDeleteArticle(article: any) {
    // @ts-ignore
    window.$('#ModeleDeleteArticle').modal('toggle');
    this.article = article as Article;
  }

  deleteArticle() {
    this.productService.deleteArticle(this.article)
      .subscribe(res => {
        let index = this.articles.indexOf(this.article);
        if (index !== -1) {
          this.articles.splice(index, 1);
        }
        // @ts-ignore
        window.$('#ModeleDeleteArticle').modal('toggle');
        this.article = new Article();
      }, error => {
        console.log(error);
      });
  }

  EditArticle(article: any) {
    // @ts-ignore
    window.$('#ModeleEditArticle').modal('toggle');
    this.article = article;
    console.log(this.article);
  }

  updateArtcle() {
    console.log(this.article);
    let obj = {
      id: this.article.id,
      categorie: this.article.categorie['@id'],
      refMachine: this.article.refArticle,
      nomMachine: this.article.name,
      quantiteTotal: this.article.quantiteTotal
    };

    this.productService.updateArticle(obj)
      .subscribe(res => {
        // @ts-ignore
        window.$('#ModeleEditArticle').modal('toggle');
        this.article = new Article();
      }, error => {
        console.log(error);
      });
  }

  closeModelEdit() {
    // @ts-ignore
    window.$('#ModeleEditArticle').modal('hide');
    // @ts-ignore
    window.$('#ModeleDeleteArticle').modal('hide');
    this.ngOnInit();
    this.article = new Article();
  }

  // searchArticle(value) {
  //   this.articles = [];
  //   if (value == '') {
  //     this.getAllArticles();
  //   } else {
  //     this.productService.searchArticle(value)
  //       .subscribe(res => {
  //         for (let article of res as Array<Article>) {
  //           this.productService.getoneCategorie(article.categorie)
  //             .subscribe(res => {
  //               // @ts-ignore
  //               article.category = res.name;
  //               this.articles.push(article);
  //               this.articles.sort(function(a, b) {
  //                 let comparison = 0;
  //                 if (a.id > b.id) {
  //                   comparison = 1;
  //                 } else if (a.id < b.id) {
  //                   comparison = -1;
  //                 }
  //                 return comparison;
  //               });
  //             });
  //         }
  //         console.log(this.articles);
  //       }, error => {
  //         console.log(error);
  //       });
  //   }
  //
  // }


  ArticleDetails(article) {
    let art = article['id'];
    this.router.navigateByUrl('dashboard/articles/' + art);
  }

  ArticleValide(article: Article) {
    let istrue = false;
    if (
      !article.activteArticleSerAchat0
    ) {
      istrue = true;
    }
    if (
      !article.activteArticleSerAchat1
    ) {
      istrue = true;
    }
    if (
      !article.activteArticleSerTransitaire0
    ) {
      istrue = true;
    }
    if (
      !article.activteArticleSerInfo1
    ) {
      istrue = true;
    }
    if (
      !article.activteArticleSerInfo0
    ) {
      istrue = true;
    }
    return istrue;
  }


  // Id	Ref Article	nom Article	Categorie	Quantite
  print() {
    let data: Array<any> = [];
    let obj = {
      id: undefined,
      Reference_Article: undefined,
      nom_Article: undefined,
      Categorie: undefined,
      Quantite: undefined
    };
    this.articles.forEach((value, key) => {
      obj.id = key + 1;
      obj.Reference_Article = value.refArticle;
      obj.nom_Article = value.name;
      obj.Categorie = value.categorie.name;
      obj.Quantite = value.quantiteTotal;
      data.push(obj);
      obj = {
        id: undefined,
        Reference_Article: undefined,
        nom_Article: undefined,
        Categorie: undefined,
        Quantite: undefined
      };
    });

    function buildTableBody(data, columns) {
      var body = [];
      body.push(columns);
      data.forEach(function(row) {
        var dataRow = [];
        columns.forEach(function(column) {
          dataRow.push(row[column].toString());
        });
        body.push(dataRow);
      });
      return body;
    }

    function table(data, columns) {
      return {
        table: {
          headerRows: 1,
          widths: ['auto', 'auto', 'auto', 'auto', 'auto'],
          body: buildTableBody(data, columns)
        }
      };
    }


    const documentDefinition = {
      pageSize: 'A5',

      // by default we use portrait, you can change it to landscape if you wish
      pageOrientation: 'landscape',

      // [left, top, right, bottom] or [horizontal, vertical] or just a number for equal margins
      pageMargins: [40, 60, 40, 60],
      content: [
        table(data, ['id',
          'Reference_Article',
          'nom_Article',
          'Categorie',
          'Quantite'
        ])
      ]
    };

    pdfMake.createPdf(documentDefinition).download('Articles.pdf');
  }
}
