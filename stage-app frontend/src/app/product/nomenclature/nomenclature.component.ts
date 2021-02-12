import {Component, OnInit} from '@angular/core';
import {Model} from '../../model/Model';
import {Nomenclature} from '../../model/Nomenclature';
import {TypeNomenclature} from '../../model/TypeNomenclature';
import {Categorie} from '../../model/Categorie';
import {Article} from '../../model/Article';
import {ProductService} from '../../Services/product.service';
import {AuthenticationService} from '../../Services/authentication.service'
import pdfMake from 'pdfmake/build/pdfmake';

@Component({
  selector: 'app-nomenclature',
  templateUrl: './nomenclature.component.html',
  styleUrls: ['./nomenclature.component.css']
})
export class NomenclatureComponent implements OnInit {
  nomenclatures;
  nomenclature = new Nomenclature();
  typeNomencatures;
  page: number = 1;
  showLoadingIndicator = false;
  error = {
    image: null,
    typeNomen: null,
    refIntern: null,
    nomFr: null,
    nomAr: null,
    nomEn: null,
    codeSage: null,
    codeShort: null,
    designation: null
  };
  categories: Array<Categorie>;
  articles: Array<Article>;
  models: Array<Model>;

  constructor(
    private productService: ProductService,
    public authService: AuthenticationService
  ) {
    this.nomenclature.typeNomenclature = new TypeNomenclature();
  }

  async ngOnInit() {
    this.showLoadingIndicator = true;

    await this.productService.nomenclatures.then(data => {
      this.nomenclatures = data['hydra:member'];
      this.showLoadingIndicator = false;
    }, error => {
      console.log('Error: ', error);
    });


    if (this.categories == null) {
      await this.productService.categories.then(data => {
        this.categories = data['hydra:member'];
        this.showLoadingIndicator = false;
      }, error => {
        console.log('Error: ', error);
      });
    }

    if (this.typeNomencatures == null) {
      await this.productService.typeNomenclatures.then(data => {
        this.typeNomencatures = data['hydra:member'];
        this.showLoadingIndicator = false;
      }, error => {
        console.log('Error: ', error);
      });
    }
    console.log(this.nomenclatures);

  }

  MAX_SIZE: number = 307200;
  nomenclatureImage;
  selectshow = false;

  onSelectedImageNomenclature(event) {
    this.nomenclatureImage = null;
    if (event.target.files && event.target.files.length > 0) {
      if (event.target.files[0].size < this.MAX_SIZE) {
        this.nomenclatureImage = event.target.files[0];
        this.error.image = null;
      } else {
        this.error.image = 'size est supérieure que 300kb';
      }
    } else {
      this.error.image = 'select image';
    }
  }

  validationInput(nomenclature: Nomenclature) {
    let vide = false;
    if (nomenclature.typeNomenclature == null || nomenclature.typeNomenclature == '') {
      this.error.typeNomen = 'Type required';
      vide = true;
    } else {
      this.error.typeNomen = null;
    }

    if (nomenclature.refIntern == null || nomenclature.refIntern == '') {
      this.error.refIntern = 'ref Intern required';
      vide = true;
    } else {
      this.error.refIntern = null;
    }

    // @ts-ignore
    if (nomenclature.nomFr == null || nomenclature.nomFr == '') {
      this.error.nomFr = 'Nom Francais required';
      vide = true;
    } else {
      this.error.nomFr = null;
    }

    if (nomenclature.nomAr == null || nomenclature.nomAr == '') {
      this.error.nomAr = 'Nom Arabe required';
      vide = true;
    } else {
      this.error.nomAr = null;
    }

    if (nomenclature.nomEn == null || nomenclature.nomEn == '') {
      this.error.nomEn = 'Nom Anglais required';
      vide = true;
    } else {
      this.error.nomEn = null;
    }

    if (nomenclature.codeSage == null || nomenclature.codeSage == '') {
      this.error.codeSage = 'Code Sage required';
      vide = true;
    } else {
      this.error.codeSage = null;
    }

    if (nomenclature.codeShort == null || nomenclature.codeShort == '') {
      this.error.codeShort = 'Code Short required';
      vide = true;
    } else {
      this.error.codeShort = null;
    }

    if (nomenclature.designation == null || nomenclature.designation == '') {
      this.error.designation = 'designation required';
      vide = true;
    } else {
      this.error.designation = null;
    }

    if (this.nomenclatureImage == null) {
      this.error.image = 'select image';
      vide = true;
    } else {
      this.error.image = null;
    }
    return vide;
  }

  AddNomenclature() {
    this.showLoadingIndicator = true;
    if (this.validationInput(this.nomenclature) == true) {
      this.showLoadingIndicator = false;
      return;
    }
    if (typeof this.nomenclature.typeNomenclature == 'object') {
      this.nomenclature.typeNomenclature = this.nomenclature.typeNomenclature['@id'];
    }
    this.productService.postNomenclature('/nomenclatures', this.nomenclature)
      .subscribe(res => {
        this.nomenclature = res as Nomenclature;
        this.nomenclature.image = this.nomenclatureImage;
        this.productService.uploadImageNomenclature(this.nomenclature)
          .subscribe(res => {
            this.nomenclatures.push(res);
            // @ts-ignore
            window.$('#myModalModel').modal('toggle');
            this.showLoadingIndicator = false;
            this.nomenclature = new Nomenclature();
            this.nomenclature.typeNomenclature = new TypeNomenclature();
            this.nomenclatureImage = null;
          }, err => {
            console.log(err);
          });
      }, err => {
        console.log(err);
      });
  }

  EditNomenclature(piece: any) {
    // @ts-ignore
    window.$('#NomenclatureEditmodal').modal('toggle');
    this.nomenclature = piece;
    this.nomenclatureImage = this.nomenclature.image;
    this.error.image = null;
  }

  async closeModelEdit() {
    // @ts-ignore
    window.$('#NomenclatureEditmodal').modal('toggle');
    this.nomenclature = new Nomenclature();
    this.nomenclature.typeNomenclature = new TypeNomenclature();
    this.showLoadingIndicator = true;
    this.nomenclatures = null;
    await this.productService.getNomenclatures().then(data => {
      this.nomenclatures = data['hydra:member'];
      console.log(this.productService.nomenclatures);
      this.showLoadingIndicator = false;
    }, error => {
      console.log('Error: ', error);
    });
  }

  updateNomenclature() {
    if (this.validationInput(this.nomenclature) == true) {
      return;
    }
    if (this.nomenclature.image != this.nomenclatureImage) {
      this.nomenclature.image = undefined;
    }
    if (typeof this.nomenclature.typeNomenclature == 'object') {
      this.nomenclature.typeNomenclature = this.nomenclature.typeNomenclature['@id'];
    }

    this.productService.updateNomenclature('/nomenclatures/' + this.nomenclature.id, this.nomenclature)
      .subscribe(res => {
        this.nomenclature.image = this.nomenclatureImage;
        const index: number = this.nomenclatures.indexOf(this.nomenclature);
        if (index !== -1) {
          this.nomenclature[index] = res;
          this.nomenclature.typeNomenclature = this.typeNomencatures.find(a => a['@id'] == this.nomenclature.typeNomenclature);
        }
        this.productService.uploadImageNomenclature(this.nomenclature)
          .subscribe(res => {
            // @ts-ignore
            this.nomenclature.image = res.image;
            // @ts-ignore
            window.$('#NomenclatureEditmodal').modal('toggle');
            this.showLoadingIndicator = false;
          }, err => {
            console.log(err);
          });
      }, err => {
        console.log(err);
      });
  }

  confirmationDeleteNomenclature(piece: any) {
    // @ts-ignore
    window.$('#ModalDeletePiece').modal('toggle');
    this.nomenclature = piece;
  }

  closeModeldelete() {
    // @ts-ignore
    window.$('#ModalDeletePiece').modal('hide');
    // @ts-ignore
    window.$('#ModalAddModelToNomenclature').modal('hide');
    this.nomenclature = new Nomenclature();
    this.nomenclature.typeNomenclature = new TypeNomenclature();
  }

  deleteNomenclature() {
    this.showLoadingIndicator = true;
    this.productService.deleteNomenclature(this.nomenclature)
      .subscribe(res => {
        let index = this.nomenclatures.indexOf(this.nomenclature);
        if (index !== -1) {
          this.nomenclatures.splice(index, 1);
        }
        // @ts-ignore
        window.$('#ModalDeletePiece').modal('toggle');
        this.nomenclature = new Nomenclature();
        this.nomenclature.typeNomenclature = new TypeNomenclature();
        this.showLoadingIndicator = false;
      }, err => {
        console.log(err);
      });
  }

  addModelToNomenclature(piece) {
    // @ts-ignore
    window.$('#ModalAddModelToNomenclature').modal('toggle');
    this.nomenclature = piece;
  }

  articlesByCat;

  async categorieOnSelect(value) {
    this.selectshow = true;
    await this.productService.articles.then(data => {
      this.articles = data['hydra:member'];
      this.articlesByCat = this.articles.filter(a => a.categorie['@id'] == value);
    }, error => {
      console.log('Error: ', error);
    });
  }

  modelsByArticle;

  async ArticleOnSelect(value) {
    await this.productService.models.then(data => {
      this.models = data['hydra:member'];
      this.modelsByArticle = this.models.filter(a => a.article['@id'] == value);
    }, error => {
      console.log('Error: ', error);
    });
  }

  addModelselectedToNomenclature(model) {
    this.nomenclature.models.push(model['@id']);
  }

  modelfind(model) {
    let index = this.nomenclature.models.indexOf(model);

    console.log(index);
    console.log(this.nomenclature.models);
    if (index !== -1) {
      return true;
    }
  }

  confirmAddModelToNomenclature() {
    let obj = {
      models: []
    };

    for (let model of this.nomenclature.models) {
      if (typeof model == 'object') {
        model = model['@id'];
      }
      obj.models.push(model);
    }
    this.productService.updateNomenclature('/nomenclatures/' + this.nomenclature.id, obj)
      .subscribe(res => {
        this.nomenclature = res as Nomenclature;
        console.log(this.nomenclature);
        this.nomenclatures.forEach((value, key) => {
          if (value.id == this.nomenclature.id) {
            this.nomenclatures[key] = this.nomenclature;
          }
        });
        this.nomenclature = new Nomenclature();
        // @ts-ignore
        window.$('#ModalAddModelToNomenclature').modal('hide');
      }, error => {
        console.log(error);
      });
  }

  showModelsOfNomeclature(piece) {
    this.nomenclature = piece;
    console.log(this.nomenclature);
    // @ts-ignore
    window.$('#showModelsofNomenclature').modal('toggle');
    this.models = this.nomenclature.models;
  }

  deleteModelsofNomenclature(model: any) {
    if (confirm('vous été sur?')) {

      console.log(model);
      this.productService.deleteModelofNomenclatures(this.nomenclature, model)
        .subscribe(res => {
          let index1 = this.models.indexOf(model);
          let index2 = this.nomenclature.models.indexOf(model);
          console.log(index1);
          console.log(index2);

          if (index1 !== -1 && index2 !== -1) {
            this.models.splice(index1, 1);
            this.nomenclature.models.splice(index2, 1);
          }
        }, err => {
          console.log(err);
        });
    }
  }

  print() {
    let data: Array<any> = [];
    let obj = {
      id: undefined,
      Reference_Interne: undefined,
      nom_Fr_Ar_En: undefined,
      designation: undefined,
      Code_Short: undefined,
      Code_Sage: undefined,
      type: undefined
    };
    this.nomenclatures.forEach((value, key) => {
      obj.id = key+1;
      obj.Reference_Interne = value.refIntern;
      obj.nom_Fr_Ar_En = value.nomFr + ' ' + value.nomAr + ' ' + value.nomEn;
      obj.designation = value.designation;
      obj.Code_Sage = value.codeSage;
      obj.Code_Short = value.codeShort;
      obj.type = value.typeNomenclature.typeName;
      data.push(obj);
      obj = {
        id: undefined,
        Reference_Interne: undefined,
        nom_Fr_Ar_En: undefined,
        designation: undefined,
        Code_Short: undefined,
        Code_Sage: undefined,
        type: undefined
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
          widths: ['auto', "auto", "auto", "auto", "auto", "auto", "auto"],
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
          'Reference_Interne',
          'nom_Fr_Ar_En',
          'designation',
          'Code_Short',
          'Code_Sage',
          'type'
        ])
      ]
    };

    pdfMake.createPdf(documentDefinition).download('Piece de rechange.pdf');

  }
}
