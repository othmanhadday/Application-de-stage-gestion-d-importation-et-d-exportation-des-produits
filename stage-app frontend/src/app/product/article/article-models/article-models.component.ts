import {Component, OnDestroy, OnInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {Article} from '../../../model/Article';
import {ProductService} from '../../../Services/product.service';
import {AuthenticationService} from '../../../Services/authentication.service';
import {Model} from '../../../model/Model';
import {Commentaire} from '../../../model/Commentaire';
import {CommentaireService} from '../../../Services/commentaire.service';
import {SSEService} from '../../../Services/sse.service';

@Component({
  selector: 'app-article-models',
  templateUrl: './article-models.component.html',
  styleUrls: ['./article-models.component.css']
})
export class ArticleModelsComponent implements OnInit, OnDestroy {
  article: any;
  commentaire = new Commentaire();

  constructor(
    public authService: AuthenticationService,
    public commentaireService: CommentaireService,
    private sseService: SSEService,
    private productService: ProductService,
    private route: ActivatedRoute
  ) {
    this.article = new Article();
  }

  private link;

  async ngOnInit() {
    this.showLoadingIndicator = true;
    this.link = this.route.snapshot.params.article;


    let data = await this.productService.articles;
    this.article = data['hydra:member'].find(a => a.id == this.link);

    console.log(this.link);
    console.log(this.article);


    this.nbrshowCommenteEnd = this.article.commentaires.length;
    this.nbrshowCommenteStart = this.nbrshowCommenteEnd - 5;
    this.showLoadingIndicator = false;

  }


  SerAchatNiv1ActivateModel(model: Model) {
    model.activteArticleSerAchat1 = true;
    this.productService.updateModel(model)
      .subscribe(res => {
        console.log(res);
      }, error => {
        console.log(error);
      });
  }

  SerAchatNiv0ActivateModel(model: Model) {
    model.activteArticleSerAchat0 = true;
    this.productService.updateModel(model)
      .subscribe(res => {
        console.log(res);
      }, error => {
        console.log(error);
      });
  }

  SerTransitaireNiv0ActivateModel(model: Model) {
    model.activteArticleSerTransitaire0 = true;
    this.productService.updateModel(model)
      .subscribe(res => {
        console.log(res);
      }, error => {
        console.log(error);
      });
  }

  SerInformatiqueNiv1ActivateModel(model: Model) {
    model.activteArticleSerInfo1 = true;
    this.productService.updateModel(model)
      .subscribe(res => {
        console.log(res);
      }, error => {
        console.log(error);
      });
  }

  SerInformatiqueNiv0ActivateModel(model: Model) {
    model.activteArticleSerInfo0 = true;
    this.productService.updateModel(model)
      .subscribe(res => {
        console.log(res);
      }, error => {
        console.log(error);
      });
  }

  SerAchatNiv1ActivateArtle(article: Article) {
    article.activteArticleSerAchat1 = true;
    let obj = {
      id: article.id,
      activteArticleSerAchat1: article.activteArticleSerAchat1
    };
    this.productService.updateArticle(obj)
      .subscribe(res => {
        console.log(res);
      }, error => {
        console.log(error);
      });
  }

  SerAchatNiv0ActivateArtle(article: Article) {
    article.activteArticleSerAchat0 = true;
    let obj = {
      id: article.id,
      activteArticleSerAchat0: article.activteArticleSerAchat0
    };
    this.productService.updateArticle(obj)
      .subscribe(res => {
        console.log(res);
      }, error => {
        console.log(error);
      });
  }

  SerTransitaireNiv0ActivateArtle(article: Article) {
    article.activteArticleSerTransitaire0 = true;
    let obj = {
      id: article.id,
      activteArticleSerTransitaire0: article.activteArticleSerTransitaire0
    };
    this.productService.updateArticle(obj)
      .subscribe(res => {
        console.log(res);
      }, error => {
        console.log(error);
      });
  }

  SerInformatiqueNiv1ActivateArtle(article: Article) {
    article.activteArticleSerInfo1 = true;
    let obj = {
      id: article.id,
      activteArticleSerInfo1: article.activteArticleSerInfo1
    };
    this.productService.updateArticle(obj)
      .subscribe(res => {
        console.log(res);
      }, error => {
        console.log(error);
      });
  }

  SerInformatiqueNiv0ActivateArtle(article: Article) {
    article.activteArticleSerInfo0 = true;
    let obj = {
      id: article.id,
      activteArticleSerInfo0: article.activteArticleSerInfo0
    };
    this.productService.updateArticle(obj)
      .subscribe(res => {
        console.log(res);
      }, error => {
        console.log(error);
      });
  }

  modelValide(model: Model) {
    let istrue = false;
    if (
      !model.activteArticleSerAchat0
    ) {
      istrue = true;
    }
    if (
      !model.activteArticleSerAchat1
    ) {
      istrue = true;
    }
    if (
      !model.activteArticleSerTransitaire0
    ) {
      istrue = true;
    }
    if (
      !model.activteArticleSerInfo1
    ) {
      istrue = true;
    }
    if (
      !model.activteArticleSerInfo0
    ) {
      istrue = true;
    }
    return istrue;
  }

  newCommentaire() {
    this.showLoadingIndicator = true;
    this.commentaire.article = this.article.id;
    console.log(this.commentaire);
    this.commentaireService.addNewCommentaire(this.commentaire)
      .subscribe(res => {
        console.log(res);
        this.article.commentaires.push(res);
        this.nbrshowCommenteEnd = this.article.commentaires.length;
        this.nbrshowCommenteStart = this.nbrshowCommenteEnd - 5;

        this.commentaire = new Commentaire();
        this.showLoadingIndicator = false;
      }, error => {
        console.log(error);
      });
  }

  nbrshowCommenteEnd: number;
  nbrshowCommenteStart: number;
  showmore = false;
  showLoadingIndicator = false;

  showMoreCommentaire() {
    this.showmore = true;
    this.nbrshowCommenteStart = 0;
  }

  showlessCommentaire() {
    this.showmore = false;
    this.nbrshowCommenteStart = this.nbrshowCommenteEnd - 5;
  }

  ngOnDestroy(): void {
    this.link = null;
  }


}
