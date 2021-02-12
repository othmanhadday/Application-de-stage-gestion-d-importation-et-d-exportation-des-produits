import {Component, OnInit} from '@angular/core';
import {FicheArrivageService} from '../../Services/fiche-arrivage.service';
import {AuthenticationService} from '../../Services/authentication.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FicheArrivageAchat} from '../../model/FicheArrivageAchat';
import {ArticleFicheArrivageAchat} from '../../model/ArticleFicheArrivageAchat';
import {ProductService} from '../../Services/product.service';
import {mod} from 'ngx-bootstrap/chronos/utils';
import {Commentaire} from '../../model/Commentaire';
import {SSEService} from '../../Services/sse.service';
import {FicheArrivageMagasinier} from '../../model/FicheArrivageMagasinier';

@Component({
  selector: 'app-fiche-arrivage-by-id',
  templateUrl: './fiche-arrivage-by-id.component.html',
  styleUrls: ['./fiche-arrivage-by-id.component.css']
})
export class FicheArrivageByIdComponent implements OnInit {
  allFcheArrvage;
  conteneurs;
  artFiche;
  ficheArrivage = new FicheArrivageAchat();
  showLoadingIndicator = false;

  constructor(
    public ficheArrivageService: FicheArrivageService,
    private productService: ProductService,
    public authService: AuthenticationService,
    private route: ActivatedRoute,
    private sseService: SSEService,
    private router: Router
  ) {
  }

  async ngOnInit() {
    this.showLoadingIndicator = true;
    let id = this.route.snapshot.params.id;
    console.log(id);

    this.allFcheArrvage = await this.ficheArrivageService.allFicheArrivage;
    this.ficheArrivageService.currentFicheArrivage = this.allFcheArrvage['hydra:member'].find(a => a.id == id);
    this.ficheArrivage = this.ficheArrivageService.currentFicheArrivage;

    await this.ficheArrivageService.allFicheArrivage.then(data => {
      this.depots = data['hydra:member'];
    }, error => {
      console.log('Error: ', error);
    });


    if (this.authService.user && this.authService.user.role && this.authService.user.role.service.name + ' ' + this.authService.user.role.niveau.name == 'Service Magasinier niveau 0') {
      await this.ficheArrivageService.depots.then(data => {
        this.depots = data['hydra:member'];
      }, error => {
        console.log('Error: ', error);
      });
    }

    if (this.authService.user && this.authService.user.role && this.authService.user.role.service.name + ' ' + this.authService.user.role.niveau.name == 'Service Achat niveau 2') {
      if (this.categories == null) {
        await this.productService.categories.then(data => {
          this.categories = data['hydra:member'];
        }, error => {
          console.log('Error: ', error);
        });
      }
    }
    if (this.ficheArrivage) {
      this.nbrshowCommenteEnd = this.ficheArrivage.commentaires.length;
      this.nbrshowCommenteStart = this.nbrshowCommenteEnd - 5;
      this.showLoadingIndicator = false;
    }
    const url = new URL('http://localhost:3000/.well-known/mercure');
    url.searchParams.append('topic', 'http://localhost:8000/updateFicheArrivage');
    await this.sseService.getServerSentEvent(url.toString())
      .subscribe(async data => {
        console.log(data.message);
        if (JSON.parse(data).message != null) {
          console.log(data);
          this.ficheArrivageService.allFicheArrivage = await this.ficheArrivageService.getAllFicheArrivage();
          console.log(this.ficheArrivageService.allFicheArrivage);
          this.ficheArrivageService.currentFicheArrivage = this.ficheArrivageService.allFicheArrivage['hydra:member'].find(a => a.id == id);
          this.ficheArrivage = this.ficheArrivageService.currentFicheArrivage;
          this.nbrshowCommenteEnd = this.ficheArrivage.commentaires.length;
          this.nbrshowCommenteStart = this.nbrshowCommenteEnd - 5;
          this.showLoadingIndicator = false;


          console.log(this.ficheArrivage);
        }
      }, err => {
        console.log(err);
      });
  }

  async SerAchatNiv1ActivateFicheArrivage(ficheArrivage: FicheArrivageAchat) {
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let obj = {
        'activteArticleSerAchat1': true
      };
      await this.ficheArrivageService.putData('/fiche_arrivage_achats/' + ficheArrivage.id, obj).toPromise()
        .then(res => {
          // @ts-ignore
          ficheArrivage.activteArticleSerAchat1 = res.activteArticleSerAchat1;
        }, err => {
          console.log(err);
        });
      let res = await this.ficheArrivageService.postData('/api/updateFicheArrivageRealTime/' + ficheArrivage.id, '').toPromise();
    }
  }

  async SerAchatNiv0ActivateFicheArrivage(ficheArrivage: FicheArrivageAchat) {
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let obj = {
        'activteArticleSerAchat0': true
      };
      await this.ficheArrivageService.putData('/fiche_arrivage_achats/' + ficheArrivage.id, obj).toPromise()
        .then(data => {
          // @ts-ignore
          ficheArrivage.activteArticleSerAchat0 = data.activteArticleSerAchat0;
        }, err => {
          console.log(err);
        });
      let res = await this.ficheArrivageService.postData('/api/updateFicheArrivageRealTime/' + ficheArrivage.id, '').toPromise();
    }
  }

  async SerTransitaireNiv0ActivateFicheArrivage(ficheArrivage: FicheArrivageAchat) {
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let obj = {
        'activteArticleSerTransitaire0': true
      };
      await this.ficheArrivageService.putData('/fiche_arrivage_achats/' + ficheArrivage.id, obj).toPromise()
        .then(res => {
          // @ts-ignore
          ficheArrivage.activteArticleSerTransitaire0 = res.activteArticleSerTransitaire0;
        }, err => {
          console.log(err);
        });
      let res = await this.ficheArrivageService.postData('/api/updateFicheArrivageRealTime/' + ficheArrivage.id, '').toPromise();
    }
  }

  async SerInformatiqueNiv1ActivateFicheArrivage(ficheArrivage: FicheArrivageAchat) {
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let obj = {
        'activteArticleSerInfo1': true
      };
      console.log(ficheArrivage.activteArticleSerInfo1);
      await this.ficheArrivageService.putData('/fiche_arrivage_achats/' + ficheArrivage.id, obj).toPromise()
        .then(res => {
          // @ts-ignore
          ficheArrivage.activteArticleSerInfo1 = res.activteArticleSerInfo1;
        }, err => {
          console.log(err);
        });
      let res = await this.ficheArrivageService.postData('/api/updateFicheArrivageRealTime/' + ficheArrivage.id, '').toPromise();
    }
  }

  async SerInformatiqueNiv0ActivateFicheArrivage(ficheArrivage: FicheArrivageAchat) {
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let obj = {
        'activteArticleSerInfo0': true
      };
      console.log(ficheArrivage.activteArticleSerInfo0);
      await this.ficheArrivageService.putData('/fiche_arrivage_achats/' + ficheArrivage.id, obj).toPromise()
        .then(res => {
          // @ts-ignore
          ficheArrivage.activteArticleSerInfo0 = res.activteArticleSerInfo0;
        }, err => {
          console.log(err);
        });
      let res = await this.ficheArrivageService.postData('/api/updateFicheArrivageRealTime/' + ficheArrivage.id, '').toPromise();
    }
  }

  FicheArrivageValide() {
    let istrue = false;
    if (this.ficheArrivage &&
      this.ficheArrivage.activteArticleSerAchat0 == true &&
      this.ficheArrivage.activteArticleSerAchat1 == true &&
      this.ficheArrivage.activteArticleSerTransitaire0 == true &&
      this.ficheArrivage.activteArticleSerInfo1 == true &&
      this.ficheArrivage.activteArticleSerInfo0 == true
    ) {
      istrue = true;
    }
    return istrue;
  }

  async showModalOfUpdateQteConteneur(artFiche: ArticleFicheArrivageAchat) {
    if (!this.conteneurs) {
      await this.ficheArrivageService.conteneurs.then(data => {
        this.conteneurs = data['hydra:member'];
      }, error => {
        console.log(error);
      });
    }
    this.artFiche = artFiche;
    // @ts-ignore
    window.$('#EditQteConteneurModal').modal('show');
  }

  async updateQteConteneur() {
    this.showLoadingIndicator = true;
    let obj = {
      'conteneur': '/conteneurs/' + this.artFiche.conteneur.id,
      'quantiteServAchat': this.artFiche.quantiteServAchat
    };
    let resp = this.ficheArrivageService.putData('/article_fiche_arrivage_achats/' + this.artFiche.id, obj).toPromise();
    this.artFiche.conteneur = this.conteneurs.find(a => a.id == this.artFiche.conteneur.id);
    this.showLoadingIndicator = false;
    // @ts-ignore
    window.$('#EditQteConteneurModal').modal('hide');
  }

  async deleteItemOfFicheArrivage(artFiche: ArticleFicheArrivageAchat) {
    this.showLoadingIndicator = true;
    this.artFiche = artFiche;
    if (confirm('Êtes-vous sûr de bien vouloir supprimer cet élément ? ') == true) {
      let resp = this.ficheArrivageService.deleteData('/api/deleteArticleFicheArrivage/' + this.artFiche.id).toPromise();
      let index = this.ficheArrivage.articleFicheArrivageAchats.indexOf(artFiche);
      if (index != -1) {
        this.ficheArrivage.articleFicheArrivageAchats.splice(index, 1);
      }
    }
    this.showLoadingIndicator = false;
  }


  categories;
  articles;
  models;
  articlesByCategorie;
  selectshow: boolean;
  modelsByArticle;
  error = {
    quantiteServAchat: null,
    conteneur: null,
    image: null
  };

  async categorieOnSelect(value: string) {
    this.selectshow = true;
    if (this.articles == null) {
      await this.productService.articles.then(data => {
        this.articles = data['hydra:member'];
      }, error => {
        console.log('Error: ', error);
      });
    }
    this.articlesByCategorie = this.articles.filter(a => a['categorie'] == value);
  }

  async ArticleOnSelect(value: string) {
    this.modelsByArticle = null;
    if (this.models == null) {
      await this.productService.models.then(data => {
        this.models = data['hydra:member'];
      }, error => {
        console.log('Error: ', error);
      });
    }
    if (!this.conteneurs) {
      await this.ficheArrivageService.conteneurs.then(data => {
        this.conteneurs = data['hydra:member'];
      }, error => {
        console.log(error);
      });
    }
    this.modelsByArticle = this.models.filter(a => a['article'] == value);
    this.productService.getAllModels('/models')
      .subscribe(res => {
        let models = res['hydra:member'];
      }, error => {
        console.log(error);
      });

  }

  modelfind(model) {
    for (let artFiche of this.ficheArrivage.articleFicheArrivageAchats) {
      if (artFiche.model['id'] == model['id']) {
        return true;
      }
    }
  }

  async addModelselectedToNomenclature(value: any, model: any) {
    this.showLoadingIndicator = true;
    if (value.quantite == '') {
      this.error.quantiteServAchat = 'Quantite Required';
      this.showLoadingIndicator = false;
      return;
    } else {
      this.error.quantiteServAchat = null;
    }
    if (value.conteneur == '') {
      this.error.conteneur = 'Conteneur Required';
      this.showLoadingIndicator = false;
      return;
    } else {
      this.error.conteneur = null;
    }
    let obj = {
      ficheArrivageAchat: this.ficheArrivage['@id'],
      model: model['@id'],
      conteneur: value.conteneur['@id'],
      quantiteServAchat: value.quantite
    };
    let resArtFiche = await this.ficheArrivageService.postData('/article_fiche_arrivage_achats', obj).toPromise();
    // @ts-ignore
    resArtFiche.model = model;
    // @ts-ignore
    resArtFiche.conteneur = value.conteneur;
    // @ts-ignore
    this.ficheArrivage.articleFicheArrivageAchats.push(resArtFiche);

    this.showLoadingIndicator = false;
  }


  showModalToAddImage(artFiche: ArticleFicheArrivageAchat) {
    this.artFiche = artFiche;
    // @ts-ignore
    window.$('#ModalAddImageToItemsFicheArrivage').modal('show');
  }

  MAX_SIZE: number = 1048576;

  //Upload ImageOutsdeConteneur
  async onAddImageOutSideConteneurToFicheArticle(event) {
    this.showLoadingIndicator = true;
    let index = this.ficheArrivage.articleFicheArrivageAchats.indexOf(this.artFiche);
    let size = 0;
    let images = [];
    if (event.target.files) {
      for (let file of event.target.files) {
        size += file.size;
      }
      if (size <= this.MAX_SIZE) {
        images = event.target.files;
        this.error.image = null;
      } else {
        this.error.image = 'size est supérieure que 1mg';
      }
    }
    let formData = new FormData();
    for (let img of images) {
      formData.append('images[]', img);
    }
    await this.ficheArrivageService.postData('/api/uploadImagesOutSideConteneurToArticleFicheArrivage/' + this.artFiche.id,
      formData).toPromise().then(data => {
      this.artFiche = data;
    }, error => {
      error.toLocaleString();
    });

    if (index != -1) {
      this.ficheArrivage.articleFicheArrivageAchats[index] = this.artFiche;
    }
    this.showLoadingIndicator = false;

  }

  //Upload ImageInsideConteneur
  async onAddImageInSideConteneurToFicheArticle(event) {
    this.showLoadingIndicator = true;
    let index = this.ficheArrivage.articleFicheArrivageAchats.indexOf(this.artFiche);
    let size = 0;
    let images = [];
    if (event.target.files) {
      for (let file of event.target.files) {
        size += file.size;
      }
      if (size <= this.MAX_SIZE) {
        images = event.target.files;
        this.error.image = null;
      } else {
        this.error.image = 'size est supérieure que 1mg';
      }
    }
    let formData = new FormData();
    for (let img of images) {
      formData.append('images[]', img);
    }
    await this.ficheArrivageService.postData('/api/uploadImagesInSideConteneurToArticleFicheArrivage/' + this.artFiche.id,
      formData).toPromise().then(data => {
      this.artFiche = data;
    }, error => {
      error.toLocaleString();
    });

    if (index != -1) {
      this.ficheArrivage.articleFicheArrivageAchats[index] = this.artFiche;
    }
    this.showLoadingIndicator = false;
  }

  async deleteImageOutSideConteneur(img) {
    if (confirm('Êtes-vous sûr de bien vouloir supprimer cet élément ? ') == true) {
      this.showLoadingIndicator = true;
      let index = this.artFiche.imagesOutSide.indexOf(img);
      let resDelete = await this.ficheArrivageService.deleteData('/api/deleteImageOutSideConteneurArticleFicheArrivage/' + img.id).toPromise();
      if (index != -1) {
        this.artFiche.imagesOutSide.splice(index, 1);
      }
      this.showLoadingIndicator = false;
    }
  }

  async deleteImageInSideConteneur(img) {
    if (confirm('Êtes-vous sûr de bien vouloir supprimer cet élément ? ') == true) {
      this.showLoadingIndicator = true;
      let index = this.artFiche.imagesInside.indexOf(img);
      let resDelete = await this.ficheArrivageService.deleteData('/api/deleteImageInSideConteneurArticleFicheArrivage/' + img.id).toPromise();
      if (index != -1) {
        this.artFiche.imagesInside.splice(index, 1);
      }
      this.showLoadingIndicator = false;
    }
  }

  async deleteFicheArrivage() {
    if (confirm('Êtes-vous sûr de bien vouloir supprimer cet Fiche Arrivage ? ') == true) {
      this.showLoadingIndicator = true;
      let index;
      let resDelete = await this.ficheArrivageService.deleteData('/api/deleteFicheArrivage/' + this.ficheArrivage.id).toPromise();
      await this.ficheArrivageService.allFicheArrivage.then(data => {
        data = data['hydra:member'];
        index = data.indexOf(this.ficheArrivage);
        if (index != -1) {
          data.splice(index, 1);
        }
      }, err => {
        console.log(err);
      });
      this.showLoadingIndicator = false;
      this.router.navigateByUrl('/dashboard/show-fiches-arrivages');
    }
  }

  commentaire: Commentaire = new Commentaire();

  async newCommentaire() {
    this.showLoadingIndicator = true;
    this.commentaire.ficheArrivageAchat = this.ficheArrivage.id;
    console.log(this.commentaire);
    await this.ficheArrivageService.postData('/api/addCommentaireFicheArrivage', this.commentaire).toPromise()
      .then(data => {
        this.ficheArrivage.commentaires.push(data as Commentaire);
        console.log(data);
        this.commentaire = new Commentaire();
        this.nbrshowCommenteEnd = this.ficheArrivage.commentaires.length;
        this.nbrshowCommenteStart = this.nbrshowCommenteEnd - 5;
        this.showLoadingIndicator = false;
      }, err => {
        console.log(err);
      });
    let res = await this.ficheArrivageService.postData('/api/updateFicheArrivageRealTime/' + this.ficheArrivage.id, '').toPromise();
  }

  nbrshowCommenteEnd: number;
  nbrshowCommenteStart: number;
  showmore = false;
  depots;

  showMoreCommentaire() {
    this.showmore = true;
    this.nbrshowCommenteStart = 0;
  }

  showlessCommentaire() {
    this.showmore = false;
    this.nbrshowCommenteStart = this.nbrshowCommenteEnd - 5;
  }

  async informSerAchat1() {
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let obj = {
        ficheArrivageId: this.ficheArrivage.id,
        role: 'Service Achat niveau 1',
        name: 'Verifier la fiche Arrivage ' + this.ficheArrivage.id
      };
      await this.ficheArrivageService.postData('/api/sendAlert', obj).toPromise().then(data => {
        console.log(data);
      });
    }
  }

  async informSerAchat0() {
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let obj = {
        ficheArrivageId: this.ficheArrivage.id,
        role: 'Service Achat niveau 0',
        name: 'Verifier la fiche Arrivage ' + this.ficheArrivage.id
      };
      await this.ficheArrivageService.postData('/api/sendAlert', obj).toPromise().then(data => {
        console.log(data);
      });
    }
  }

  async informSerTransitaire0() {
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let obj = {
        ficheArrivageId: this.ficheArrivage.id,
        role: 'Service Transitaire niveau 0',
        name: 'Verifier la fiche Arrivage ' + this.ficheArrivage.id
      };
      await this.ficheArrivageService.postData('/api/sendAlert', obj).toPromise().then(data => {
        console.log(data);
      });
    }
  }

  async informSerInfo1() {
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let obj = {
        ficheArrivageId: this.ficheArrivage.id,
        role: 'Service Informatique niveau 1',
        name: 'Verifier la fiche Arrivage ' + this.ficheArrivage.id
      };
      await this.ficheArrivageService.postData('/api/sendAlert', obj).toPromise().then(data => {
        console.log(data);
      });
    }
  }

  async informSerInfo0() {
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let obj = {
        ficheArrivageId: this.ficheArrivage.id,
        role: 'Service Informatique niveau 0',
        name: 'Verifier la fiche Arrivage ' + this.ficheArrivage.id
      };
      await this.ficheArrivageService.postData('/api/sendAlert', obj).toPromise().then(data => {
        console.log(data);
      });
    }
  }

  goToFicheArrivageTransitaire(artFiche: ArticleFicheArrivageAchat) {
    this.router.navigateByUrl('dashboard/fiche-arrivage-Trans/' + artFiche.id);
  }

  async selectDepot(value: string) {
    this.showLoadingIndicator = true;
    let fiche = await this.ficheArrivageService.postData(
      '/api/selectDepotFicheArrivage/' + this.ficheArrivage.id,
      {
        'depot': value
      }).toPromise();

    this.ficheArrivage.depot = this.depots.find(a => a['id'] == value);
    this.showLoadingIndicator = false;
  }

  editDepot = false;

  async updateDepot(value: string) {
    console.log(value);
    this.showLoadingIndicator = true;
    let fiche = await this.ficheArrivageService.putData(
      '/fiche_arrivage_achats/' + this.ficheArrivage.id,
      {
        'depot': '/depots/' + value
      }).toPromise();
    this.ficheArrivage.depot = this.depots.find(a => a['id'] == value);
    this.editDepot = false;
    this.showLoadingIndicator = false;
  }

  async dateArriveeConteneurDepot(artFiche) {
    this.showLoadingIndicator = true;
    let ficheArrivageMagasinier = new FicheArrivageMagasinier();
    ficheArrivageMagasinier.dateArriveDepot = new Date();
    ficheArrivageMagasinier.user = '/users/' + this.authService.user.id;
    ficheArrivageMagasinier.articleFicheArrivage = '/article_fiche_arrivage_achats/' + artFiche.id;
    let resMaga = await this.ficheArrivageService.postData('/fiche_arrivage_magasiniers', ficheArrivageMagasinier).toPromise();
    // @ts-ignore
    ficheArrivageMagasinier.id = resMaga.id;
    ficheArrivageMagasinier.ficheArrivageMagasinierManutentionnaires = [];
    artFiche.ficheArrivageMagasiniers.push(ficheArrivageMagasinier);
    console.log(artFiche);
    ficheArrivageMagasinier = new FicheArrivageMagasinier();
    this.showLoadingIndicator = false;
  }

  goToFicheArrivageMagasinier(artFiche: ArticleFicheArrivageAchat) {
    this.router.navigateByUrl('dashboard/fiche-arrivage-maga/' + artFiche.id);
  }

  manutentionnaireAccessToFicheMaga(artFiche: ArticleFicheArrivageAchat) {
    if (artFiche.ficheArrivageMagasiniers[0].ficheArrivageMagasinierManutentionnaires.length > 0) {
      for (let manumaga of artFiche.ficheArrivageMagasiniers[0].ficheArrivageMagasinierManutentionnaires) {
        if (manumaga.user.id == this.authService.user.id) {
          return true;
        }
      }
    }
  }

  async finishOperation() {
    this.showLoadingIndicator = true;
    console.log(this.ficheArrivage.id);
    let fiche = await this.ficheArrivageService.putData('/fiche_arrivage_achats/' + this.ficheArrivage.id, {'finishOperation': true}).toPromise();
    this.ficheArrivage.finishOperation = true;
    this.showLoadingIndicator = false;
  }
}
