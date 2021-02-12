import {Component, OnInit} from '@angular/core';
import {ProductService} from '../../Services/product.service';
import {AuthenticationService} from '../../Services/authentication.service';
import {ArticleFicheArrivageAchat} from '../../model/ArticleFicheArrivageAchat';
import {Conteneur} from '../../model/Conteneur';
import {FicheArrivageService} from '../../Services/fiche-arrivage.service';
import {FicheArrivageAchat} from '../../model/FicheArrivageAchat';
import {Router} from '@angular/router';

@Component({
  selector: 'app-fiche-arrivage',
  templateUrl: './fiche-arrivage.component.html',
  styleUrls: ['./fiche-arrivage.component.css']
})
export class FicheArrivageComponent implements OnInit {
  categories;
  articlesByCategorie;
  selectshow: boolean;
  modelsByArticle;
  conteneur = new Conteneur();
  showLoadingIndicator = false;
  articles: any;
   models: any;

  constructor(
    public authService: AuthenticationService,
    private productService: ProductService,
    private ficheArrivageService: FicheArrivageService,
    private router: Router
  ) {
  }

  async ngOnInit() {
    // @ts-ignore
    window.$('#ModalAddModelToNomenclature').modal('toggle');
    if (this.categories == null) {
      await this.productService.categories.then(data => {
        this.categories = data['hydra:member'];
        this.showLoadingIndicator = false;
      }, error => {
        console.log('Error: ', error);
      });
    }
  }

  async categorieOnSelect(value: string) {
    this.selectshow = true;
    this.selectshow = true;
    await this.productService.articles.then(data => {
      this.articles = data['hydra:member'];
      this.articlesByCategorie = this.articles.filter(a => a.categorie['@id'] == value);
    }, error => {
      console.log('Error: ', error);
    });
  }

  async ArticleOnSelect(value: string) {
    this.modelsByArticle = null;
    await this.productService.models.then(data => {
      this.models = data['hydra:member'];
      this.modelsByArticle = this.models.filter(a => a.article['@id'] == value);
    }, error => {
      console.log('Error: ', error);
    });

    this.productService.getAllModels('/conteneurs')
      .subscribe(res => {
        this.conteneurs = res['hydra:member'];
      }, err => {
        console.log(err);
      });
  }

  modelfind(model) {
    let index = this.articleFicheArrivageAchats.indexOf(model);
    for (let artFiche of this.articleFicheArrivageAchats) {
      if (artFiche.model['id'] == model['id']) {
        return true;
      }
    }
  }

  articleFicheArrivageAchat = null;
  articleFicheArrivageAchats = [];
  error = {
    quantiteServAchat: null,
    conteneur: null,
    image: null
  };
  conteneurs;

  addModelselectedToNomenclature(value, model) {
    if (value.quantite == '') {
      this.error.quantiteServAchat = 'Quantite required';
      return;
    } else {
      this.error.quantiteServAchat = null;
    }
    this.articleFicheArrivageAchat = new ArticleFicheArrivageAchat();
    this.articleFicheArrivageAchat.quantiteServAchat = value.quantite;
    this.articleFicheArrivageAchat.model = model;
    this.articleFicheArrivageAchats.push(this.articleFicheArrivageAchat);

  }

  deleteModelofFicheArrivage(artFiche) {
    let index = this.articleFicheArrivageAchats.indexOf(artFiche);
    if (index !== -1) {
      this.articleFicheArrivageAchats.splice(index, 1);
    }
  }

  editFArrivage = false;

  editFicheArrivage() {
    this.editFArrivage = true;
  }

  addConteneurToFicheArrivage(value, artFiche) {
    let index = this.articleFicheArrivageAchats.indexOf(artFiche);
    if (index !== -1) {
      this.articleFicheArrivageAchats[index].conteneur = this.conteneurs.find(a => a['@id'] == value);
    }
  }


  addNewConteneur() {
    if (!this.conteneur.numConteneur) {
      this.error.conteneur = 'Num Conteneur Required';
      return;
    } else {
      this.error.conteneur = null;
    }
    this.ficheArrivageService.postData('/conteneurs', this.conteneur)
      .subscribe(res => {
        this.conteneurs.push(res);
        // @ts-ignore
        window.$('#modalAddConteneur').modal('toggle');
      }, err => {
        console.log(err);
      });

  }


  async uploadAllDataFicheArrivage() {
    this.showLoadingIndicator = true;
    for (let artFiche of this.articleFicheArrivageAchats) {
      if (!artFiche.conteneur) {
        this.error.conteneur = 'Conteneur required';
        this.showLoadingIndicator = false;

        return;
      } else {
        this.error.conteneur = null;
      }
      if (!artFiche.quantiteServAchat) {
        this.error.image = 'Quantite required';
        this.showLoadingIndicator = false;
        return;
      } else {
        this.error.image = null;
      }
    }
    //Post FicheArrivage et les items de chaque conteneurs
    let ficheArrivage = new FicheArrivageAchat();
    ficheArrivage.user = '/users/' + this.authService.user.id;
    ficheArrivage.createdAt = new Date();
    ficheArrivage.updatedAt = new Date();
    let resfich = await this.ficheArrivageService.postData('/fiche_arrivage_achats', ficheArrivage).toPromise();
    ficheArrivage = resfich as FicheArrivageAchat;

    for (let art of this.articleFicheArrivageAchats) {
      let artFicheArrivageAchat = new ArticleFicheArrivageAchat();
      artFicheArrivageAchat.conteneur = '/conteneurs/' + art.conteneur.id;
      artFicheArrivageAchat.model = '/models/' + art.model.id;
      artFicheArrivageAchat.quantiteServAchat = art.quantiteServAchat;
      artFicheArrivageAchat.ficheArrivageAchat = ficheArrivage['@id'];
      let res = this.ficheArrivageService.postData('/article_fiche_arrivage_achats', artFicheArrivageAchat).toPromise();
    }

    //Send Notification
    let Notification = {
      roles: ['Service Achat niveau 1', 'Service Achat niveau 0', 'Service Transitaire niveau 0', 'Service Informatique niveau 1', 'Service Informatique niveau 0'],
      link: '/fiche-arrivage/' + ficheArrivage.id,
      name: this.authService.user.fullName + ' à Ajouter un nouveau Arrivage : ' + ficheArrivage.id + ' tu dois vérifier cet Arrivage !! '
    };
    let sendNotification = await this.ficheArrivageService.postData('/api/sendNotification', Notification).toPromise();

    console.log(sendNotification);
    this.showLoadingIndicator = false;
    this.router.navigateByUrl('dashboard/fiche-arrivage/' + ficheArrivage.id);
    this.ficheArrivageService.allFicheArrivage = this.ficheArrivageService.getAllFicheArrivage();

  }


}
