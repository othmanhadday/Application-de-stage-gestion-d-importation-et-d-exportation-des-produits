import {Component, ElementRef, OnInit, ViewChild} from '@angular/core';
import {FicheArrivageService} from '../../Services/fiche-arrivage.service';
import {AuthenticationService} from '../../Services/authentication.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FicheArrivageFinance} from '../../model/FicheArrivageFinance';
import 'rxjs/Rx';
import {parseDate} from 'ngx-bootstrap/chronos';
import {formatDate} from '@angular/common';

import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';

// import jsPDF from 'jspdf';
import jspdf from 'jspdf';
import html2canvas from 'html2canvas';


import {HttpClient} from '@angular/common/http';


pdfMake.vfs = pdfFonts.pdfMake.vfs;

@Component({
  selector: 'app-fiche-arrivage-finance',
  templateUrl: './fiche-arrivage-finance.component.html',
  styleUrls: ['./fiche-arrivage-finance.component.css']
})
export class FicheArrivageFinanceComponent implements OnInit {
  public showLoadingIndicator = false;
  private urlId;
  ficheArrivage;
  nbrHq = 0;
  artNbr = 0;

  constructor(
    private ficheArrivageService: FicheArrivageService,
    public authService: AuthenticationService,
    private route: ActivatedRoute,
    private http: HttpClient,
    private router: Router
  ) {

  }
  async ngOnInit() {
    this.showLoadingIndicator = true;
    await this.route.params.subscribe(params => {
      this.urlId = params.id;
    });

    let data = await this.ficheArrivageService.allFicheArrivage;
    this.ficheArrivageService.currentFicheArrivage = data['hydra:member'].find(a => a.id == this.urlId);
    this.ficheArrivage = this.ficheArrivageService.currentFicheArrivage;

    console.log(this.ficheArrivage);
    this.showLoadingIndicator = false;

    this.nbrHq = this.numberHq(this.ficheArrivage);
    this.showLoadingIndicator = false;

  }


  incrementNbr() {
    this.artNbr += 1;
  }

  numberHq(ficheArrivage) {
    let nbr: number = 0;
    for (let i = 0; i < ficheArrivage.articleFicheArrivageAchats.length; i++) {
      for (let j = 0; j < i; j++) {
        if (ficheArrivage.articleFicheArrivageAchats[i].conteneur.id != ficheArrivage.articleFicheArrivageAchats[j].conteneur.id) {
          console.log(ficheArrivage.articleFicheArrivageAchats[i].conteneur.id + '  :  ' + ficheArrivage.articleFicheArrivageAchats[j].conteneur.id);
          nbr++;
        }
      }
    }
    return nbr;
  }

  async cleExterneEcartvalide(artFiche) {
    let obj = {
      'user': this.authService.user.id,
      'articleFicheArrivage': artFiche.id,
      'cleExterne': true
    };
    let ficheArrivageFinance = await this.ficheArrivageService.postData(
      '/api/verifyCleExterne', obj).toPromise();
    artFiche.ficheArrivageFinances.push(ficheArrivageFinance);
  }

  async cleDouanValide(artFiche: any) {
    let ficheArrivageFinance = new FicheArrivageFinance();
    ficheArrivageFinance.articleFicheArrivage = artFiche['@id'];
    ficheArrivageFinance.user = '/users/' + this.authService.user.id;
    ficheArrivageFinance.cleDouan = true;
    let ficheArrivageFinanceRes = await this.ficheArrivageService.postData(
      '/fiche_arrivage_finances', ficheArrivageFinance).toPromise();
    artFiche.ficheArrivageFinances.push(ficheArrivageFinanceRes);
    console.log(artFiche.ficheArrivageFinances);

  }

  async cleDouanNotValide(artFiche: any) {
    let obj = {
      'user': this.authService.user.id,
      'articleFicheArrivage': artFiche.id,
      'cleDouan': false
    };
    let ficheArrivageFinance = await this.ficheArrivageService.postData(
      '/api/verifyCleDouan', obj).toPromise();
    console.log(ficheArrivageFinance);
    artFiche.ficheArrivageFinances.push(ficheArrivageFinance);
  }

  async tempSup3h(artFiche) {
    console.log(artFiche.ficheArrivageFinances[0].id);
    let obj = {
      'user': this.authService.user.id,
      'articleFicheArrivage': artFiche.id,
      'temps': true
    };
    let ficheArrivageFinance = await this.ficheArrivageService.postData(
      '/api/verifyTemps/' + artFiche.ficheArrivageFinances[0].id, obj).toPromise();
    artFiche.ficheArrivageFinances[0].verifierTemps = true;
  }

  between2Time(artFiche) {
    // @ts-ignore
    let valu = (parseDate(artFiche.ficheArrivageMagasiniers[0].dateArriveDepot) - parseDate(artFiche.ficheArrivageTransitaires[0].dateSortiePort));
    valu = valu / (1000 * 60 * 60);
    return Math.floor(valu) + 'h:' + Math.round((valu % 1) * 100) + 'min';
  }

  async print() {
    let formattedDt = formatDate(this.ficheArrivage.articleFicheArrivageAchats[0].ficheArrivageMagasiniers[0].dateArriveDepot,
      'dd-MM-yyyy HH:mm', 'en_US');

    let data: Array<any> = [];
    let obj = {
      id: undefined,
      Reference_machine: undefined,
      Numero_conteneurs: undefined,
      Quantite_transitaire: undefined,
      Quantite_Prévu: undefined,
      Quantite_Reçu: undefined,
      Inventaire_Pysique: undefined,
      risque_ecart: undefined
    };
    let imagesAchatOutside = [];

    var result = this.ficheArrivage.articleFicheArrivageAchats.forEach((value, key) => {
      obj.id = key;
      obj.Reference_machine = value.model.refMachine;
      obj.Numero_conteneurs = value.conteneur.numConteneur;
      obj.Quantite_transitaire = value.quantiteServTransitaire;
      obj.Quantite_Prévu = value.quantiteServAchat;
      obj.Quantite_Reçu = value.quantiteServMagasinier;
      if (value.ficheArrivageTransitaires[0] && value.ficheArrivageTransitaires[0].inventairePhysique == true) {
        obj.Inventaire_Pysique = 'complet';
      } else if (value.ficheArrivageTransitaires[0] && value.ficheArrivageTransitaires[0].inventairePhysique == false) {
        obj.Inventaire_Pysique = 'no complet';
      }
      data.push(obj);
      obj = {
        id: undefined,
        Reference_machine: undefined,
        Numero_conteneurs: undefined,
        Quantite_transitaire: undefined,
        Quantite_Prévu: undefined,
        Quantite_Reçu: undefined,
        Inventaire_Pysique: undefined,
        risque_ecart: undefined
      };

      imagesAchatOutside = value.imagesOutSide;
      console.log(value.imagesOutSide);
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
          widths: ['auto', 70, 60, 60, 60, 60, 70],
          body: buildTableBody(data, columns)
        }
      };
    }

    // this.getBase64ImageFromURL('https://127.0.0.1:8000/uploads/FicheArrivage/Arrivage=17/Article=ataer/ServiceMagasinier/InSideConteneur/azert-5f88d6ee09207.jpeg', function (dataUrl) {
    //   console.log(dataUrl)
    // })
    //

    // let images = async (images) => {
    //   let data = [];
    //   for (let img of images) {
    //     data.push(
    //       {
    //         image: await this.getBase64ImageFromURL(
    //           'https://127.0.0.1:8000/uploads/FicheArrivage/Arrivage=17/Article=ataer/ServiceMagasinier/InSideConteneur/azert-5f88d6ee09207.jpeg'
    //         )
    //       }
    //     );
    //   }
    //   return data;
    // };
    //
    // console.log(await images(imagesAchatOutside));

    const documentDefinition = {
      pageSize: 'A5',

      // by default we use portrait, you can change it to landscape if you wish
      pageOrientation: 'landscape',

      // [left, top, right, bottom] or [horizontal, vertical] or just a number for equal margins
      pageMargins: [40, 60, 40, 60],
      content: [
        {
          style: 'tableExample',
          table: {
            widths: [510],
            body: [
              [{text: 'Arrivage  : ' + this.ficheArrivage.id + ' à ' + this.ficheArrivage.depot.name, alignment: 'center'}],
              [{
                text: 'Date d\'arrivée prévu au port : ' + formattedDt
                , alignment: 'center'
              }],
            ]
          }
        },
        table(
          data, ['id',
            'Reference_machine',
            'Numero_conteneurs',
            'Quantite_transitaire',
            'Quantite_Prévu',
            'Quantite_Reçu',
            'Inventaire_Pysique'
            // {text: 'risque d\'ecart', alignment: 'center'}
          ]),
        // await images(imagesAchatOutside)
        {
          image: await this.getBase64ImageFromURL(
            'https://127.0.0.1:8000/uploads/FicheArrivage/Arrivage=17/Article=ataer/ServiceMagasinier/InSideConteneur/azert-5f88d6ee09207.jpeg'
          )
        }
      ]
    };

    pdfMake.createPdf(documentDefinition).download('Score_Details.pdf');

  }


  getBase64ImageFromURL(url) {

    return new Promise((resolve, reject) => {
      var img = new Image();
      img.setAttribute('crossorigin', 'anonymous');

      img.onload = () => {
        var canvas = document.createElement('canvas');

        canvas.width = img.width;
        canvas.height = img.height;
        var ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0);
        var dataURL = canvas.toDataURL('image/jpeg');
        resolve(dataURL);
      };
      img.onerror = error => {
        reject(error);
      };
      img.src = url;
      console.log(img);
    });
  }


  fetchImage(uri) {
    return new Promise(function(resolve, reject) {
      const image = new window.Image();
      image.onload = function() {
        var canvas = document.createElement('canvas');
        canvas.width = 200;
        canvas.height = 200;
        canvas.getContext('2d').drawImage(image, 0, 0);
        resolve(canvas.toDataURL('image/png'));
      };
      image.onerror = function(params) {
        reject(new Error('Cannot fetch image ' + uri + '.'));
      };
      image.src = uri;
    });
  }


}
