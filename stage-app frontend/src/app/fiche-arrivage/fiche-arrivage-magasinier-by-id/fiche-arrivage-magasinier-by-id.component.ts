import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {ArticleFicheArrivageAchat} from '../../model/ArticleFicheArrivageAchat';
import {FicheArrivageService} from '../../Services/fiche-arrivage.service';
import {AuthenticationService} from '../../Services/authentication.service';
import {FicheArrivageMagasinier} from '../../model/FicheArrivageMagasinier';
import {FicheArrivageMagasinierManutentionnaire} from '../../model/FicheArrivageMagasinierManutentionnaire';
import {Notification} from '../../model/Notification';
import {SSEService} from '../../Services/sse.service';

@Component({
  selector: 'app-fiche-arrivage-magasinier-by-id',
  templateUrl: './fiche-arrivage-magasinier-by-id.component.html',
  styleUrls: ['./fiche-arrivage-magasinier-by-id.component.css']
})
export class FicheArrivageMagasinierByIdComponent implements OnInit {
  urlId;
  articleFicheArrivage;
  showLoadingIndicator = false;
  showNomenclatures = false;
  showAddQteImages = false;
  showupdateQteImages = false;
  error = {
    image: null,
    imageOutSide: null,
    imageInside: null,
    quantite: null
  };

  constructor(
    private ficheArrivageService: FicheArrivageService,
    private router: Router,
    public authService: AuthenticationService,
    private route: ActivatedRoute,
    private sseService: SSEService
  ) {
  }

  async ngOnInit() {
    this.showLoadingIndicator = true;
    this.urlId = this.route.snapshot.params.id;
    console.log(this.urlId);

    if (!this.ficheArrivageService.currentFicheArrivage) {
      this.router.navigateByUrl('dashboard/show-fiches-arrivages');
      return;
    }

    this.articleFicheArrivage = await this.ficheArrivageService.currentFicheArrivage.articleFicheArrivageAchats.find(a => a.id == this.urlId);
    this.showLoadingIndicator = false;
    console.log(this.articleFicheArrivage);

    this.showLoadingIndicator = false;

    const url = new URL('http://localhost:3000/.well-known/mercure');
    url.searchParams.append('topic', 'http://localhost:8000/updateFicheArrivage');
    await this.sseService.getServerSentEvent(url.toString())
      .subscribe(async data => {
        if (JSON.parse(data).message) {
          console.log(data);
          this.ficheArrivageService.allFicheArrivage = await this.ficheArrivageService.getAllFicheArrivage();
          this.ficheArrivageService.currentFicheArrivage = this.ficheArrivageService.allFicheArrivage['hydra:member'].find(a => a.id == this.ficheArrivageService.currentFicheArrivage.id);
          this.articleFicheArrivage = await this.ficheArrivageService.currentFicheArrivage.articleFicheArrivageAchats.find(a => a.id == this.urlId);

          this.showLoadingIndicator = false;
        }
      });
  }

  showNomenclaturesTable() {
    this.showLoadingIndicator = true;
    this.articleFicheArrivage.model.nomenclatures.forEach(async (value, key) => {
      if (typeof value == 'string') {
        value = await this.ficheArrivageService.getData(value).toPromise();
        value.typeNomenclature = await this.ficheArrivageService.getData(value.typeNomenclature).toPromise();
        this.articleFicheArrivage.model.nomenclatures[key] = value;
      }
      if (key == this.articleFicheArrivage.model.nomenclatures.length - 1) {
        this.showLoadingIndicator = false;
        this.showNomenclatures = this.showNomenclatures == false ? this.showNomenclatures = true : this.showNomenclatures = false;
      }
    });
  }

  MAX_SIZE: number = 1048576;

  onAddImagesToFicheArticleMagasinierOutSide(event, articleFicheArrivage) {
    this.articleFicheArrivage = articleFicheArrivage;
    this.articleFicheArrivage.ficheArrivageMagasiniers[0].imagesOutSide = [];
    this.articleFicheArrivage.urlOutside = [];
    let size = 0;
    if (event.target.files) {
      for (let file of event.target.files) {
        size += file.size;
      }
      if (size <= this.MAX_SIZE) {
        this.articleFicheArrivage.ficheArrivageMagasiniers[0].imagesOutSide = event.target.files;
        for (let i = 0; i < event.target.files.length; i++) {
          var reader = new FileReader();
          reader.readAsDataURL(event.target.files[i]); // read file as data url
          reader.onload = (event) => { // called once readAsDataURL is completed
            this.articleFicheArrivage.urlOutside.push(event.target.result);
          };
        }
        this.error.imageOutSide = null;
      } else {
        this.error.imageOutSide = 'size est supérieure que 1mg';
      }
    }
  }

  onAddImagesToFicheArticleMagasinierInside(event, articleFicheArrivage) {
    this.articleFicheArrivage = articleFicheArrivage;
    this.articleFicheArrivage.ficheArrivageMagasiniers[0].imagesInside = [];
    this.articleFicheArrivage.urlInside = [];
    let size = 0;
    if (event.target.files) {
      for (let file of event.target.files) {
        size += file.size;
      }
      if (size <= this.MAX_SIZE) {
        this.articleFicheArrivage.ficheArrivageMagasiniers[0].imagesInside = event.target.files;
        for (let i = 0; i < event.target.files.length; i++) {
          var reader = new FileReader();
          reader.readAsDataURL(event.target.files[i]); // read file as data url
          reader.onload = (event) => { // called once readAsDataURL is completed
            this.articleFicheArrivage.urlInside.push(event.target.result);
          };
        }
        this.error.imageInside = null;
      } else {
        this.error.imageInside = 'size est supérieure que 1mg';
      }
    }
  }

  async uploadQteImageMagasinier() {
    this.showLoadingIndicator = true;
    if (!this.articleFicheArrivage.quantiteServMagasinier) {
      this.error.quantite = 'Quantite Required';
      this.showLoadingIndicator = false;
      return;
    } else {
      this.error.quantite = null;
    }
    if (this.articleFicheArrivage.ficheArrivageMagasiniers[0].imagesInside.length == 0) {
      this.error.imageInside = 'image Required';
      this.showLoadingIndicator = false;
      return;
    } else {
      this.error.imageInside = null;
    }
    if (this.articleFicheArrivage.ficheArrivageMagasiniers[0].imagesOutSide.length == 0) {
      this.error.imageOutSide = 'image Required';
      this.showLoadingIndicator = false;
      return;
    } else {
      this.error.imageOutSide = null;
    }

    let formData = new FormData();
    formData.append('user', this.authService.user.id + '');
    formData.append('quantiteMagasinier', this.articleFicheArrivage.quantiteServMagasinier);
    formData.append('ficheArrivageMagasiinier', this.articleFicheArrivage.ficheArrivageMagasiniers[0].id);
    for (let img of this.articleFicheArrivage.ficheArrivageMagasiniers[0].imagesOutSide) {
      formData.append('imagesOutSide[]', img);
    }
    for (let img of this.articleFicheArrivage.ficheArrivageMagasiniers[0].imagesInside) {
      formData.append('imagesInside[]', img);
    }

    let resMagasinier = await this.ficheArrivageService.postData('/api/addFicheArrivageMagasinier', formData).toPromise();
    this.articleFicheArrivage.ficheArrivageMagasiniers[0].imagesInside = [];
    this.articleFicheArrivage.ficheArrivageMagasiniers[0].imagesOutSide = [];
    // @ts-ignore
    this.articleFicheArrivage.ficheArrivageMagasiniers[0].imagesInside = resMagasinier.imagesInside;
    // @ts-ignore
    this.articleFicheArrivage.ficheArrivageMagasiniers[0].imagesOutSide = resMagasinier.imagesOutSide;
    this.articleFicheArrivage.model.quantiteTotal += this.articleFicheArrivage.quantiteServMagasinier;
    console.log(this.articleFicheArrivage.ficheArrivageMagasiniers[0]);
    this.showAddQteImages = false;
    this.showLoadingIndicator = false;
  }

  showAllImagesMagasinier(ficheArrivageMagasinier: any) {
    // @ts-ignore
    window.$('#showImageMagasinier').modal('show');
    console.log(ficheArrivageMagasinier);
    ficheArrivageMagasinier.imagesInside.forEach(async (value, key) => {
      if (typeof value == 'string') {
        value = await this.ficheArrivageService.getData(value).toPromise();
        ficheArrivageMagasinier.imagesInside[key] = value;
      }
    });
    ficheArrivageMagasinier.imagesOutSide.forEach(async (value, key) => {
      if (typeof value == 'string') {
        value = await this.ficheArrivageService.getData(value).toPromise();
        ficheArrivageMagasinier.imagesOutSide[key] = value;
      }
    });
  }

  quantiteMaga;

  showUpdateCard() {
    this.quantiteMaga = this.articleFicheArrivage.quantiteServMagasinier;
    this.showupdateQteImages = true;
  }

  imageUpdateOutside = [];
  imageUpdateInside = [];

  onUpdateImagesToFicheArticleMagasinierOutside(event, articleFicheArrivage) {
    this.articleFicheArrivage = articleFicheArrivage;
    this.imageUpdateOutside = [];
    this.articleFicheArrivage.urlOutside = [];
    let size = 0;
    if (event.target.files) {
      for (let file of event.target.files) {
        size += file.size;
      }
      if (size <= this.MAX_SIZE) {
        this.imageUpdateOutside = event.target.files;
        for (let i = 0; i < event.target.files.length; i++) {
          var reader = new FileReader();
          reader.readAsDataURL(event.target.files[i]); // read file as data url
          reader.onload = (event) => { // called once readAsDataURL is completed
            this.articleFicheArrivage.urlOutside.push(event.target.result);
          };
        }
        this.error.image = null;
      } else {
        this.error.image = 'size est supérieure que 1mg';
      }
    }
  }

  onUpdateImagesToFicheArticleMagasinierInside(event, articleFicheArrivage) {
    this.articleFicheArrivage = articleFicheArrivage;
    this.imageUpdateInside = [];
    this.articleFicheArrivage.urlInside = [];
    let size = 0;
    if (event.target.files) {
      for (let file of event.target.files) {
        size += file.size;
      }
      if (size <= this.MAX_SIZE) {
        this.imageUpdateInside = event.target.files;
        for (let i = 0; i < event.target.files.length; i++) {
          var reader = new FileReader();
          reader.readAsDataURL(event.target.files[i]); // read file as data url
          reader.onload = (event) => { // called once readAsDataURL is completed
            this.articleFicheArrivage.urlInside.push(event.target.result);
          };
        }
        this.error.image = null;
      } else {
        this.error.image = 'size est supérieure que 1mg';
      }
    }
  }

  async updateQteImageMagasinier() {
    this.showLoadingIndicator = true;
    if (!this.articleFicheArrivage.quantiteServMagasinier) {
      this.error.quantite = 'Quantite Required';
      this.showLoadingIndicator = false;
      return;
    } else {
      this.error.quantite = null;
    }

    let formData = new FormData();
    formData.append('quantiteMagasinier', this.articleFicheArrivage.quantiteServMagasinier);

    for (let img of this.imageUpdateOutside) {
      formData.append('imagesOutSide[]', img);
    }
    for (let img of this.imageUpdateInside) {
      formData.append('imagesInside[]', img);
    }
    let resMagasinier = await this.ficheArrivageService.postData('/api/updateFicheArrivageMagasinier/' + this.articleFicheArrivage.ficheArrivageMagasiniers[0].id,
      formData).toPromise();
    if (this.imageUpdateOutside.length > 0) {
      console.log(resMagasinier);
      this.articleFicheArrivage.ficheArrivageMagasiniers[0].imagesOutSide = [];
      // @ts-ignore
      this.articleFicheArrivage.ficheArrivageMagasiniers[0].imagesOutSide = resMagasinier.imagesOutSide;
    }
    if (this.imageUpdateInside.length > 0) {
      this.articleFicheArrivage.ficheArrivageMagasiniers[0].imagesInside = [];
      // @ts-ignore
      this.articleFicheArrivage.ficheArrivageMagasiniers[0].imagesInside = resMagasinier.imagesInside;
    }
    this.articleFicheArrivage.model.quantiteTotal -= this.quantiteMaga;
    this.articleFicheArrivage.model.quantiteTotal = parseInt(this.articleFicheArrivage.model.quantiteTotal) + parseInt(this.articleFicheArrivage.quantiteServMagasinier);
    this.articleFicheArrivage.ficheArrivageMagasiniers[0].verifierMagasinier2 = false;
    this.showupdateQteImages = false;
    this.showLoadingIndicator = false;
  }


  manutentionnaires;
  searchManu: any;
  manutentionnaireFiltred = null;

  async showModalAddManu() {
    // @ts-ignore
    window.$('#addManuToFicheArrMagasinier').modal('show');
    this.manutentionnaires = await this.ficheArrivageService.manutentonnaires;
    this.manutentionnaires.forEach((value, key) => {
      for (let image of value.images) {
        // @ts-ignore
        let imageEachUser = image.name;
        if (imageEachUser.indexOf('personnel') !== -1) {
          value.imageUser = imageEachUser;
        }
      }
    });
  }

  searchManutentionnaire() {
    let term = this.searchManu;
    this.manutentionnaireFiltred = this.manutentionnaires.filter(function(tag) {
      return tag.fullName.indexOf(term) >= 0;
    });
  }

  async addManuToFicheArrMaga(manu) {
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let obj = {
        ficheArrivageMagasinier: this.articleFicheArrivage.ficheArrivageMagasiniers[0].id,
        user: manu.id,
        link: '/fiche-arrivage-maga/' + this.urlId,
        name: this.authService.user.fullName + ' à Ajouter la quantite de fiche Arrivage : ' + this.ficheArrivageService.currentFicheArrivage.id + ' tu dois vérifier la quantite de cet Arrivage !!'
      };

      let res = await this.ficheArrivageService.postData('/api/addManuToFicheArrivageMaga', obj).toPromise();
      let manuMaga = new FicheArrivageMagasinierManutentionnaire();
      manuMaga.user = manu;
      this.articleFicheArrivage.ficheArrivageMagasiniers[0].ficheArrivageMagasinierManutentionnaires.push(manuMaga);
    }
  }

  manuIsActivate(manu) {
    if (this.articleFicheArrivage.ficheArrivageMagasiniers[0].ficheArrivageMagasinierManutentionnaires) {
      for (let manumaga of this.articleFicheArrivage.ficheArrivageMagasiniers[0].ficheArrivageMagasinierManutentionnaires) {
        if (manumaga.user.id == manu.id) {
          return true;
        }
      }
    }
  }

  quantiteMagaManuIsEqual() {
    if (this.articleFicheArrivage.ficheArrivageMagasiniers[0].ficheArrivageMagasinierManutentionnaires) {
      for (let manumaga of this.articleFicheArrivage.ficheArrivageMagasiniers[0].ficheArrivageMagasinierManutentionnaires) {
        if (manumaga.quantiteManutentionnaire) {
          if (this.articleFicheArrivage.quantiteServMagasinier != manumaga.quantiteManutentionnaire) {
            return true;
          }
        }
      }
    }
  }

  quantiteManutentionnaire;

  async addQteManu(manu) {
    console.log(manu);
    this.showLoadingIndicator = true;
    manu.quantiteManutentionnaire = this.quantiteManutentionnaire;
    let obj = {
      quantiteManutentionnaire: manu.quantiteManutentionnaire
    };
    await this.ficheArrivageService.putData('/fiche_arrivage_magasinier_manutentionnaires/' + manu.id, obj).toPromise();

    let notification = new Notification();
    notification.link = '/fiche-arrivage-maga/' + this.urlId;
    // @ts-ignore
    notification.roles = ['Service Magasinier niveau 2'];
    if (manu.quantiteManutentionnaire != this.articleFicheArrivage.quantiteServMagasinier) {
      notification.name = this.authService.user.fullName + ' Manutentionnaire n \'est pas valide la quantite de la fiche arrivage   : ' + this.ficheArrivageService.currentFicheArrivage.id + ' tu dois vérifier la quantite de cet Arrivage !!';
    } else {
      notification.name = this.authService.user.fullName + ' Manutentionnaire a valide la quantite de Magasinier dans la fiche arrivage   : ' + this.ficheArrivageService.currentFicheArrivage.id;
    }
    await this.ficheArrivageService.postData('/api/sendNotification', notification).toPromise();
    this.showLoadingIndicator = false;
  }

  async valideQteMagasinierParManutentionnaire(manu: any) {
    this.showLoadingIndicator = true;
    let resMagasinier = await this.ficheArrivageService.putData(
      '/fiche_arrivage_magasinier_manutentionnaires/' + manu.id,
      {
        'verifierMagasinier2': true
      }
    ).toPromise();
    this.showLoadingIndicator = false;
    manu.verifierMagasinier2 = true;
  }

  async notValideQteMagasinierParManutentionnaire(manu: any) {
    this.showLoadingIndicator = true;
    let resMagasinier = await this.ficheArrivageService.putData(
      '/fiche_arrivage_magasinier_manutentionnaires/' + manu.id,
      {
        'verifierMagasinier2': false
      }
    ).toPromise();
    this.showLoadingIndicator = false;
    manu.verifierMagasinier2 = false;
  }

  async valideQteMagasinierParMagaNv1() {
    this.showLoadingIndicator = true;
    let resMagasinier = await this.ficheArrivageService.putData(
      '/fiche_arrivage_magasiniers/' + this.articleFicheArrivage.ficheArrivageMagasiniers[0].id,
      {'verifierMagasinier1': true}
    ).toPromise();
    this.showLoadingIndicator = false;
    this.articleFicheArrivage.ficheArrivageMagasiniers[0].verifierMagasinier1 = true;
    let res = await this.ficheArrivageService.postData('/api/updateFicheArrivageRealTime/' + this.ficheArrivageService.currentFicheArrivage.id, '').toPromise();
  }

  async valideQteMagasinierParMagaNv0() {
    this.showLoadingIndicator = true;
    let resMagasinier = await this.ficheArrivageService.putData(
      '/fiche_arrivage_magasiniers/' + this.articleFicheArrivage.ficheArrivageMagasiniers[0].id,
      {'verifierMagasinier0': true}
    ).toPromise();
    this.showLoadingIndicator = false;
    this.articleFicheArrivage.ficheArrivageMagasiniers[0].verifierMagasinier0 = true;
    let res = await this.ficheArrivageService.postData('/api/updateFicheArrivageRealTime/' + this.ficheArrivageService.currentFicheArrivage.id, '').toPromise();
  }

  async informSerMagasinier1() {
    console.log(this.ficheArrivageService.currentFicheArrivage.id);
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let obj = {
        ficheArrivageId: this.ficheArrivageService.currentFicheArrivage.id,
        role: 'Service Magasinier niveau 1',
        name: 'Verifier la fiche Arrivage ' + this.ficheArrivageService.currentFicheArrivage.id + ' Conteneur :' + this.articleFicheArrivage.conteneur.numConteneur
      };
      await this.ficheArrivageService.postData('/api/sendAlert', obj).toPromise().then(data => {
        console.log(data);
      });
    }
  }

  async informSerMagasinier0() {
    console.log(this.ficheArrivageService.currentFicheArrivage.id);
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let obj = {
        ficheArrivageId: this.ficheArrivageService.currentFicheArrivage.id,
        role: 'Service Magasinier niveau 0',
        name: 'Verifier la fiche Arrivage ' + this.ficheArrivageService.currentFicheArrivage.id + ' Conteneur :' + this.articleFicheArrivage.conteneur.numConteneur
      };
      await this.ficheArrivageService.postData('/api/sendAlert', obj).toPromise().then(data => {
        console.log(data);
      });
    }
  }
}
