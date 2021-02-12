import {Component, OnInit} from '@angular/core';
import {ProductService} from '../../Services/product.service';
import {Model} from '../../model/Model';
import {AuthenticationService} from '../../Services/authentication.service';
import {mod} from 'ngx-bootstrap/chronos/utils';
import pdfMake from 'pdfmake/build/pdfmake';


@Component({
  selector: 'app-model-article',
  templateUrl: './model-article.component.html',
  styleUrls: ['./model-article.component.css']
})
export class ModelArticleComponent implements OnInit {
  models;
  articles;
  model = new Model();
  error = {
    image: null,
    modelArticle: null,
    modelRefMachine: null,
    modelNomMachine: null,
    modelPrix: null
  };
  showLoadingIndicator = false;
  searchValue: any;
  page: number = 1;

  constructor(private productService: ProductService,
              public authService: AuthenticationService) {
  }

  async ngOnInit() {
    this.showLoadingIndicator = true;

    if (this.models == null) {
      await this.productService.models.then(data => {
        this.models = data['hydra:member'];
        this.showLoadingIndicator = false;
      }, error => {
        console.log('Error: ', error);
      });
    }

    if (this.articles == null) {
      await this.productService.articles.then(data => {
        this.articles = data['hydra:member'];
        this.showLoadingIndicator = false;
      }, error => {
        console.log('Error: ', error);
      });
    }

    console.log(this.models);

  }

  searchArticle($event: any) {
  }

  MAX_SIZE: number = 307200;
  modelImage;

  onSelectedImageModel(event) {
    this.modelImage = null;
    if (event.target.files && event.target.files.length > 0) {
      if (event.target.files[0].size < this.MAX_SIZE) {
        this.modelImage = event.target.files[0];
        this.error.image = null;
      } else {
        this.error.image = 'size est supérieure que 300kb';
      }
    } else {
      this.error.image = 'select image';
    }
  }

  addModel() {
    this.showLoadingIndicator = true;
    if (this.validationInput(this.model) == true) {
      this.showLoadingIndicator = false;
      return;
    }
    if (typeof this.model.article == 'object') {
      this.model.article = '/articles/' + this.model.article.id;
    }
    this.productService.addNouveauModel(this.model)
      .subscribe(res => {
        this.model = res as Model;
        this.model.image = this.modelImage;
        this.productService.uploadImageModal(this.model)
          .subscribe(res => {
            // @ts-ignore
            window.$('#myModalModel').modal('toggle');
            this.showLoadingIndicator = false;
            // @ts-ignore
            res.article = this.articles.find(a => a['@id'] == res.article);
            this.models.push(res);
            this.model = new Model();
            this.modelImage = null;
          }, err => {
            console.log(err);
          });
      }, error => {
        console.log(error);
      });
  }

  validationInput(model: Model) {
    let vide = false;
    if (model.article == null || model.article == '') {
      this.error.modelArticle = 'Article required';
      vide = true;
    } else {
      this.error.modelArticle = null;
    }

    if (model.refMachine == null || model.refMachine == '') {
      this.error.modelRefMachine = 'ref Machine required';
      vide = true;
    } else {
      this.error.modelRefMachine = null;
    }

    // @ts-ignore
    if (model.nomMachine == null || model.nomMachine == '') {
      this.error.modelNomMachine = 'Nom Machine required';
      vide = true;
    } else {
      this.error.modelNomMachine = null;
    }

    if (this.modelImage == null) {
      this.error.image = 'select image';
      vide = true;
    } else {
      this.error.image = null;
    }

    if (model.quantiteTotal == null) {
      this.error.modelPrix = 'Quantite required';
      vide = true;
    } else {
      this.error.modelPrix = null;
    }

    return vide;
  }

  confirmationDeleteModel(model: Model) {
    // @ts-ignore
    window.$('#ModalDeleteModel').modal('toggle');
    this.model = model;
  }

  deleteModel() {
    this.showLoadingIndicator = true;
    this.productService.deleteModel(this.model)
      .subscribe(res => {
        let index = this.models.indexOf(this.model);
        if (index !== -1) {
          this.models.splice(index, 1);
        }
        // @ts-ignore
        window.$('#ModalDeleteModel').modal('toggle');
        this.model = new Model();
        this.showLoadingIndicator = false;
      }, err => {
        console.log(err);
      });
  }

  closeModeldelete() {
    // @ts-ignore
    window.$('#ModalDeleteModel').modal('toggle');
    this.model = new Model();
  }

  closeModelEdit() {
    // @ts-ignore
    window.$('#ModeleEditmodel').modal('toggle');
    this.ngOnInit();
    this.model = new Model();
  }

  EditModel(model: Model) {
// @ts-ignore
    window.$('#ModeleEditmodel').modal('toggle');
    this.model = model;
    this.modelImage = this.model.image;
    this.error.image = null;
  }

  updateCategorie() {
    this.showLoadingIndicator = true;
    if (this.validationInput(this.model) == true) {
      this.showLoadingIndicator = false;
      return;
    }
    if (this.model.image != this.modelImage) {
      this.model.image = undefined;
    }
    if (typeof this.model.article == 'object') {
      this.model.article = '/articles/' + this.model.article.id;
    }

    this.productService.updateModel(this.model)
      .subscribe(res => {
        this.model.image = this.modelImage;
        const index: number = this.models.indexOf(this.model);
        if (index !== -1) {
          this.model[index] = res;
          this.model.article = this.articles.find(a => a['@id'] == this.model.article);
        }
        this.productService.uploadImageModal(this.model)
          .subscribe(res => {
            // @ts-ignore
            this.model.image = res.image;
            // @ts-ignore
            window.$('#ModeleEditmodel').modal('toggle');
            this.showLoadingIndicator = false;
            this.model = new Model();
          }, err => {
            console.log(err);
          });
      }, err => {
        console.log(err);
      });
  }


  // Id	Ref Machine	nom Machine	Article	Quantite	image

  print() {
    let data: Array<any> = [];
    let obj = {
      id: undefined,
      Reference_Machine: undefined,
      nom_Machine: undefined,
      Article: undefined,
      Quantite: undefined
    };
    this.models.forEach((value, key) => {
      obj.id = key + 1;
      obj.Reference_Machine = value.refMachine;
      obj.nom_Machine = value.nomMachine;
      obj.Article = value.article.name;
      obj.Quantite = value.quantiteTotal;
      data.push(obj);
      obj = {
        id: undefined,
        Reference_Machine: undefined,
        nom_Machine: undefined,
        Article: undefined,
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
          'Reference_Machine',
          'nom_Machine',
          'Article',
          'Quantite'
        ])
      ]
    };

    pdfMake.createPdf(documentDefinition).download('Models.pdf');
  }
}
