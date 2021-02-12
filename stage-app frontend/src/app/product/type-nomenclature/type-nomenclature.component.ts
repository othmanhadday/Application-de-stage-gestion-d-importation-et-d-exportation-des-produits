import {Component, OnInit} from '@angular/core';
import {ProductService} from '../../Services/product.service';
import {TypeNomenclature} from '../../model/TypeNomenclature';
import {AuthenticationService} from '../../Services/authentication.service';

@Component({
  selector: 'app-type-nomenclature',
  templateUrl: './type-nomenclature.component.html',
  styleUrls: ['./type-nomenclature.component.css']
})
export class TypeNomenclatureComponent implements OnInit {
  public typeNomenclatures;
  public typeNomenclature: TypeNomenclature = {
    id: undefined,
    typeName: undefined
  };
  showLoadingIndicator = false;
  error = {
    typeName: null
  };
  page = 1;

  constructor(
    public authService: AuthenticationService,
    private productService: ProductService
  ) {
    authService.getRoles();
  }

  async ngOnInit() {
    this.showLoadingIndicator = true;
    if (this.typeNomenclatures == null) {
      await this.productService.typeNomenclatures.then(data => {
        this.typeNomenclatures = data['hydra:member'];
        this.showLoadingIndicator = false;
      }, error => {
        console.log('Error: ', error);
      });
    }

  }

  AddNewTypeNomenclature() {
    this.showLoadingIndicator = true;
    this.productService.postTypeNomenclature('/type_nomenclatures', this.typeNomenclature)
      .subscribe(res => {
        this.typeNomenclatures.push(res);
        // @ts-ignore
        window.$('#myModalTypeNomenclature').modal('toggle');
        this.showLoadingIndicator = false;
        this.typeNomenclature = new TypeNomenclature();
      }, error => {
        console.log(error);
      });
  }

  confirmDeleteTypeNomenclature(type: any) {
    // @ts-ignore
    window.$('#ModeleDeleteTypeNomenclature').modal('toggle');
    this.typeNomenclature = type;
  }

  deleteNomenclature() {
    this.productService.deleteTypeNomenclature('/type_nomenclatures/' + this.typeNomenclature.id)
      .subscribe(res => {
        const index: number = this.typeNomenclatures.indexOf(this.typeNomenclature);
        if (index !== -1) {
          this.typeNomenclatures.splice(index, 1);
        }
        // @ts-ignore
        window.$('#ModeleDeleteTypeNomenclature').modal('toggle');
        this.typeNomenclature = new TypeNomenclature();
      }, error => {
        console.log(error);
      });
  }

  editTypeNomenclature(type: any) {
    // @ts-ignore
    window.$('#ModeleEditTypeNomenclature').modal('toggle');
    this.typeNomenclature = type;

  }

  updateTypeNomenclature(value: any) {
    this.productService.updateTypeNomenclature('/type_nomenclatures/' + this.typeNomenclature.id, value)
      .subscribe(res => {
        const index: number = this.typeNomenclatures.indexOf(this.typeNomenclature);
        if (index !== -1) {
          this.typeNomenclatures[index] = res;
        }
        // @ts-ignore
        window.$('#ModeleEditTypeNomenclature').modal('toggle');
        this.typeNomenclature = new TypeNomenclature();
      }, error => {
        console.log(error);
      });
  }

  closeModal() {
    // @ts-ignore
    window.$('#ModeleDeleteTypeNomenclature').modal('hide');
    // @ts-ignore
    window.$('#ModeleEditTypeNomenclature').modal('hide');
    this.typeNomenclature = new TypeNomenclature();
  }

  typeNomenclatureSearch;

  searchType(value) {
    this.typeNomenclatureSearch = this.typeNomenclatures.filter(type => type.typeName.indexOf(value) != -1);
  }
}
