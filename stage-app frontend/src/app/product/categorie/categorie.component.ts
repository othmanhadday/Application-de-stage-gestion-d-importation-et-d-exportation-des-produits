import {Component, OnInit} from '@angular/core';
import {Categorie} from '../../model/Categorie';
import {ProductService} from '../../Services/product.service';
import {AuthenticationService} from '../../Services/authentication.service';

@Component({
  selector: 'app-categorie',
  templateUrl: './categorie.component.html',
  styleUrls: ['./categorie.component.css']
})
export class CategorieComponent implements OnInit {
  categories;
  categorie = new Categorie();
  showLoadingIndicator = false;
  error = {
    CatName: null
  };
  page: number = 1;

  constructor(
    public authService: AuthenticationService,
    private productService: ProductService) {
    this.authService.getRoles();
  }

  async ngOnInit() {
    this.showLoadingIndicator = true;
    if (this.categories == null) {
      await this.productService.categories.then(data => {
        this.categories = data['hydra:member'];
        this.showLoadingIndicator = false;
      }, error => {
        console.log('Error: ', error);
      });
    }
  }

  AddNewCategorie() {
    this.showLoadingIndicator = true;
    if (this.categorie.name == null) {
      this.error.CatName = 'Categorie name required !!!!';
      this.showLoadingIndicator = false;
      return;
    } else {
      this.error.CatName = null;
    }
    this.productService.addCategorie(this.categorie)
      .subscribe(res => {
        this.categories.push(res);
        this.categorie = new Categorie();
        this.showLoadingIndicator = false;
        // @ts-ignore
        window.$('#myModalCategorie').modal('toggle');
      }, error => {
        console.log(error);
      });
  }

  confirmationDeleteCategorie(Categorie: Categorie) {
    this.categorie = Categorie;
    // @ts-ignore
    window.$('#ModeleDeleteCategorie').modal('toggle');
  }

  deleteCategorie() {
    this.showLoadingIndicator = true;
    this.productService.deleteCategorie(this.categorie)
      .subscribe(res => {
        // @ts-ignore
        window.$('#ModeleDeleteCategorie').modal('toggle');
        const index: number = this.categories.indexOf(this.categorie);
        if (index !== -1) {
          this.categories.splice(index, 1);
        }
        this.showLoadingIndicator = false;
      }, error => {
        console.log(error);
      });
  }

  EditCategrie(cat: Categorie) {
    this.categorie = cat;
    // @ts-ignore
    window.$('#ModeleEditCategorie').modal('toggle');
  }

  updateCategorie(value: any) {
    this.showLoadingIndicator = true;
    this.productService.updateCategorie(this.categorie, value)
      .subscribe(res => {
        const index: number = this.categories.indexOf(this.categorie);
        if (index !== -1) {
          this.categories[index] = res;
        }
        // @ts-ignore
        window.$('#ModeleEditCategorie').modal('toggle');
        this.categorie = new Categorie();
        this.showLoadingIndicator = false;
      }, err => {
        console.log(err);
      });
  }

  closeModelEdit() {
    // @ts-ignore
    window.$('#ModeleEditCategorie').modal('hide');
// @ts-ignore
    window.$('#ModeleDeleteCategorie').modal('hide');
    this.categorie = new Categorie();
  }
}
