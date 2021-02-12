import {Component, OnInit} from '@angular/core';
import {FicheArrivageService} from '../../Services/fiche-arrivage.service';
import {ProductService} from '../../Services/product.service';
import {AuthenticationService} from '../../Services/authentication.service';
import {ActivatedRoute, Router} from '@angular/router';
import {SSEService} from '../../Services/sse.service';
import {ArticleFicheArrivageAchat} from '../../model/ArticleFicheArrivageAchat';
import {FicheArrivageTransitaire} from '../../model/FicheArrivageTransitaire';
import {Dume} from '../../model/Dume';

@Component({
  selector: 'app-fiche-arrivage-transitaire',
  templateUrl: './fiche-arrivage-transitaire.component.html',
  styleUrls: ['./fiche-arrivage-transitaire.component.css']
})
export class FicheArrivageTransitaireComponent implements OnInit {

  artFiche;
  showLoadingIndicator = false;
  verifierquantiteServTransitaire = false;
  error = {
    quantiteServTransitaire: null,
    imageoutside: null,
    imageinside: null,
    image: null
  };
  showAddDume = false;
  showeditDume = false;
  private ficheArrivageTransitaire: FicheArrivageTransitaire;

  constructor(
    private ficheArrivageService: FicheArrivageService,
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

    if (!this.ficheArrivageService.currentFicheArrivage) {
      this.router.navigateByUrl('dashboard/show-fiches-arrivages');
      return;
    }
    this.artFiche = await this.ficheArrivageService.currentFicheArrivage.articleFicheArrivageAchats.find(a => a.id == id);
    this.showLoadingIndicator = false;
    const url = new URL('http://localhost:3000/.well-known/mercure');
    url.searchParams.append('topic', 'http://localhost:8000/updateFicheArrivage');
    await this.sseService.getServerSentEvent(url.toString())
      .subscribe(async data => {
        if (JSON.parse(data).message) {
          console.log(data);
          this.ficheArrivageService.allFicheArrivage = await this.ficheArrivageService.getAllFicheArrivage();
          this.ficheArrivageService.currentFicheArrivage = this.ficheArrivageService.allFicheArrivage['hydra:member'].find(a => a.id == this.ficheArrivageService.currentFicheArrivage.id);
          this.artFiche = await this.ficheArrivageService.currentFicheArrivage.articleFicheArrivageAchats.find(a => a.id == id);
          console.log(this.artFiche);
          this.showLoadingIndicator = false;
        }
      });
  }

  dateTransitaire() {
    let after2Month = new Date();
    after2Month = new Date(this.ficheArrivageService.currentFicheArrivage.createdAt);
    // console.log(after2Month);
    after2Month.setMonth(after2Month.getMonth() + 1);
    // console.log(after2Month);
    if (new Date() > after2Month) {
      return true;
    }
  }


  async inventairePhysiqueOpen() {
    this.showLoadingIndicator = true;
    this.verifierquantiteServTransitaire = true;
    this.artFiche.ficheArrivageTransitaires = [];
    this.ficheArrivageTransitaire = new FicheArrivageTransitaire();
    this.ficheArrivageTransitaire.inventairePhysique = true;
    this.ficheArrivageTransitaire.articleFicheArrivage = this.artFiche.id;
    this.ficheArrivageTransitaire.user = this.authService.user.id;
    this.artFiche.ficheArrivageTransitaires.push(this.ficheArrivageTransitaire);
    await this.ficheArrivageService.putData('/article_fiche_arrivage_achats/' + this.artFiche.id, {'dateOuvertConteneur': new Date()}).toPromise()
      .then(res => {
        console.log(res);
        this.showLoadingIndicator = false;
      });
    this.showLoadingIndicator = false;
  }

  async inventairePhysiqueClose() {
    this.showLoadingIndicator = true;
    this.artFiche.ficheArrivageTransitaires = [];
    this.ficheArrivageTransitaire = new FicheArrivageTransitaire();
    this.ficheArrivageTransitaire.inventairePhysique = false;
    this.ficheArrivageTransitaire.articleFicheArrivage = this.artFiche.id;
    this.artFiche.ficheArrivageTransitaires.push(this.ficheArrivageTransitaire);
    await this.ficheArrivageService.postData('/api/addFicheArrivageTransitaireInventairePhysiqueFalse', this.ficheArrivageTransitaire).toPromise()
      .then(res => {
        console.log(res);
        this.showLoadingIndicator = false;
      });
  }

  MAX_SIZE: number = 1048576;

  onAddImageOutsideToFicheArticleServTransitaire(event) {
    let size = 0;
    this.artFiche.ficheArrivageTransitaires[0].urlOutside = [];
    this.artFiche.ficheArrivageTransitaires[0].images = [];
    if (event.target.files) {
      for (let file of event.target.files) {
        size += file.size;
      }
      if (size <= this.MAX_SIZE) {
        this.artFiche.ficheArrivageTransitaires[0].imagesOutSide = event.target.files;
        this.error.imageoutside = null;
        for (let i = 0; i < event.target.files.length; i++) {
          if (event.target.files[i]) {
            var reader = new FileReader();
            reader.readAsDataURL(event.target.files[i]); // read file as data url
            reader.onload = (event) => { // called once readAsDataURL is completed
              this.artFiche.ficheArrivageTransitaires[0].urlOutside.push(event.target.result);
            };
          }
        }
      } else {
        this.error.imageoutside = 'size est supérieure que 1mg';
      }
    }
    console.log(this.artFiche.ficheArrivageTransitaires[0]);
  }

  onAddImageInsideToFicheArticleServTransitaire(event) {
    let size = 0;
    this.artFiche.ficheArrivageTransitaires[0].urlinside = [];
    this.artFiche.ficheArrivageTransitaires[0].images = [];
    if (event.target.files) {
      for (let file of event.target.files) {
        size += file.size;
      }
      if (size <= this.MAX_SIZE) {
        this.artFiche.ficheArrivageTransitaires[0].imagesInside = event.target.files;
        this.error.imageinside = null;
        for (let i = 0; i < event.target.files.length; i++) {
          if (event.target.files[i]) {
            var reader = new FileReader();
            reader.readAsDataURL(event.target.files[i]); // read file as data url
            reader.onload = (event) => { // called once readAsDataURL is completed
              this.artFiche.ficheArrivageTransitaires[0].urlinside.push(event.target.result);
            };
          }
        }
      } else {
        this.error.imageinside = 'size est supérieure que 1mg';
      }
    }
    console.log(this.artFiche.ficheArrivageTransitaires[0]);
  }

  async saveQuantiteServTransitaire() {
    this.showLoadingIndicator = true;
    if (!this.artFiche.quantiteServTransitaire) {
      this.error.quantiteServTransitaire = 'Quantite Required';
      this.showLoadingIndicator = false;
      return;
    } else {
      this.error.quantiteServTransitaire = null;
    }

    let formData = new FormData();
    formData.append('quantiteTransitaire', this.artFiche.quantiteServTransitaire);
    formData.append('inventairePhysique', this.artFiche.ficheArrivageTransitaires[0].inventairePhysique);
    formData.append('user', this.artFiche.ficheArrivageTransitaires[0].user);
    formData.append('articleFicheArrivage', this.artFiche.ficheArrivageTransitaires[0].articleFicheArrivage);
    for (let img of this.artFiche.ficheArrivageTransitaires[0].imagesOutSide) {
      formData.append('imagesOutside[]', img);
    }
    for (let img of this.artFiche.ficheArrivageTransitaires[0].imagesInside) {
      formData.append('imagesInside[]', img);
    }
    let res = await this.ficheArrivageService.postData('/api/addFicheArrivageTransitaire', formData).toPromise();
    await this.ficheArrivageService.getCurrentFicheArrivage(this.ficheArrivageService.currentFicheArrivage.id);
    this.artFiche = await this.ficheArrivageService.currentFicheArrivage.articleFicheArrivageAchats.find(a => a.id == this.artFiche.id);
    console.log(this.artFiche);
    this.verifierquantiteServTransitaire = false;
    this.showLoadingIndicator = false;

  }


  showDumeCard(artFiche) {
    this.showAddDume = true;
  }

  dume = new Dume();

  addImageDume(event) {
    let size = 0;
    if (event.target.files) {
      if (event.target.files[0].size <= this.MAX_SIZE) {
        this.dume.image = event.target.files[0];
        this.error.image = null;
      } else {
        this.error.image = 'size est supérieure que 1mg';
      }
    }
  }

  async saveDume() {
    if (!this.artFiche.quantiteServTransitaire) {
      this.error.quantiteServTransitaire = 'quantite Required';
      return;
    } else {
      this.error.quantiteServTransitaire = null;
    }
    if (!this.dume.image) {
      this.error.image = 'Image Required';
      return;
    } else {
      this.error.image = null;
    }

    this.showLoadingIndicator = true;
    let formData = new FormData();
    formData.append('articleFicheArrivage', this.artFiche.id);
    formData.append('quantiteServTransitaire', this.artFiche.quantiteServTransitaire);
    formData.append('image', this.dume.image);

    this.ficheArrivageService.postData('/api/addDume', formData).toPromise()
      .then(res => {
        this.dume = res as Dume;
        this.artFiche.dumes.push(this.dume);
        this.showAddDume = false;
        this.showLoadingIndicator = false;
      }, err => {
        console.log(err);
        this.showLoadingIndicator = false;
      });
    console.log(this.artFiche);
  }


  showAllImagesTransitaire(ficheArrivageTransitaire) {
    // @ts-ignore
    window.$('#showImagesOfFicheArrivageTransitaire').modal('show');
  }


  showDumeCardUpdate() {
    this.showeditDume = true;
  }

  updateImageDume(event) {
    let size = 0;
    if (event.target.files) {
      if (event.target.files[0].size <= this.MAX_SIZE) {
        this.artFiche.dumes[0].image = event.target.files[0];
        this.error.image = null;
      } else {
        this.error.image = 'size est supérieure que 1mg';
      }
    }
  }

  async updateDume(dume: any) {
    this.dume = dume;
    if (!this.artFiche.quantiteServTransitaire) {
      this.error.quantiteServTransitaire = 'quantite Required';
      return;
    } else {
      this.error.quantiteServTransitaire = null;
    }
    if (!this.dume.image) {
      this.error.image = 'Image Required';
      return;
    } else {
      this.error.image = null;
    }
    this.showLoadingIndicator = true;
    await this.ficheArrivageService.putData('/article_fiche_arrivage_achats/' + this.artFiche.id,
      {'quantiteServTransitaire': this.artFiche.quantiteServTransitaire})
      .toPromise().then(async res => {
        this.showLoadingIndicator = false;
        if (typeof this.dume.image == 'object') {
          let formData = new FormData();
          formData.append('image', this.dume.image);
          await this.ficheArrivageService.postData('/api/updateDume/' + this.dume.id, formData)
            .toPromise().then(res => {
              this.artFiche.dumes[0] = res as Dume;
              this.showeditDume = false;
              this.showLoadingIndicator = false;
            });
        }
      });
  }

  showImageDume(dume) {
    // @ts-ignore
    window.$('#showImageDume').modal('show');
    this.dume = dume;
  }

  async valideQuantiteServTransitaireNv0(ficheArrivageTransitaire: any) {
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let fiche = await this.ficheArrivageService.putData('/fiche_arrivage_transitaires/' + ficheArrivageTransitaire.id, {'verifierArticlesServTransitare0': true}).toPromise();
      ficheArrivageTransitaire.verifierArticlesServTransitare0 = true;
      await this.ficheArrivageService.postData('/api/notifyMagasinierNv0',
        {
          'articleFicheArrivage': this.artFiche.id
        })
        .toPromise();
      let res = await this.ficheArrivageService.postData('/api/updateFicheArrivageRealTime/' + this.ficheArrivageService.currentFicheArrivage.id, '').toPromise();
    }
  }

  async valideQuantiteServTransitaireNv1(ficheArrivageTransitaire: any) {
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let fiche = await this.ficheArrivageService.putData('/fiche_arrivage_transitaires/' + ficheArrivageTransitaire.id, {'verifierArticlesServTransitare1': true}).toPromise();
      ficheArrivageTransitaire.verifierArticlesServTransitare1 = true;
      console.log(fiche);
      let res = await this.ficheArrivageService.postData('/api/updateFicheArrivageRealTime/' + this.ficheArrivageService.currentFicheArrivage.id, '').toPromise();
    }
  }

  async valideQuantiteServTransitaireNv2(ficheArrivageTransitaire) {
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let fiche = await this.ficheArrivageService.putData('/fiche_arrivage_transitaires/' + ficheArrivageTransitaire.id, {'verifierArticlesServTransitare2': true}).toPromise();
      ficheArrivageTransitaire.verifierArticlesServTransitare2 = true;
      console.log(fiche);
      let res = await this.ficheArrivageService.postData('/api/updateFicheArrivageRealTime/' + this.ficheArrivageService.currentFicheArrivage.id, '').toPromise();
    }
  }

  async informSerTransitaire2() {
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let obj = {
        ficheArrivageId: this.ficheArrivageService.currentFicheArrivage.id,
        role: 'Service Transitaire niveau 2',
        name: 'Verifier la fiche Arrivage ' + this.ficheArrivageService.currentFicheArrivage.id + ' Conteneur :' + this.artFiche.conteneur.numConteneur
      };
      await this.ficheArrivageService.postData('/api/sendAlert', obj).toPromise().then(data => {
        console.log(data);
      });
    }
  }

  async informSerTransitaire1() {
    console.log(this.ficheArrivageService.currentFicheArrivage.id);
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let obj = {
        ficheArrivageId: this.ficheArrivageService.currentFicheArrivage.id,
        role: 'Service Transitaire niveau 1',
        name: 'Verifier la fiche Arrivage ' + this.ficheArrivageService.currentFicheArrivage.id + ' Conteneur :' + this.artFiche.conteneur.numConteneur
      };
      await this.ficheArrivageService.postData('/api/sendAlert', obj).toPromise().then(data => {
        console.log(data);
      });
    }
  }

  async informSerTransitaire0() {
    if (confirm('Êtes-vous sûr  ? ') == true) {
      let obj = {
        ficheArrivageId: this.ficheArrivageService.currentFicheArrivage.id,
        role: 'Service Transitaire niveau 0',
        name: 'Verifier la fiche Arrivage ' + this.ficheArrivageService.currentFicheArrivage.id + ' Conteneur :' + this.artFiche.conteneur.numConteneur
      };
      await this.ficheArrivageService.postData('/api/sendAlert', obj).toPromise().then(data => {
        console.log(data);
      });
    }
  }

  dateSortieConteneur(ficheArrivageTransitaire: FicheArrivageTransitaire) {
    this.showLoadingIndicator = true;
    this.ficheArrivageService.putData(ficheArrivageTransitaire['@id'], {'dateSortiePort': new Date()})
      .subscribe(res => {
        ficheArrivageTransitaire.dateSortiePort = new Date();
        this.showLoadingIndicator = false;
      });
  }
}
