import {Injectable} from '@angular/core';
import {AuthenticationService} from './authentication.service';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Categorie} from '../model/Categorie';
import {Article} from '../model/Article';
import {User} from '../model/User';
import {Model} from '../model/Model';
import {mod} from 'ngx-bootstrap/chronos/utils';
import {Nomenclature} from '../model/Nomenclature';

@Injectable({
  providedIn: 'root'
})
export class ProductService {

  categories;
  articles;
  models;
  nomenclatures;
  typeNomenclatures;

  constructor(
    private authService: AuthenticationService,
    private http: HttpClient
  ) {
    if (this.categories == null) {
      this.categories = this.getCategories();
    }
    if (this.articles == null) {
      this.articles = this.getArticles();
    }
    if (this.models == null) {
      this.models = this.getModels();
    }
    if (this.nomenclatures == null) {
      this.nomenclatures = this.getNomenclatures();
    }
    if (this.typeNomenclatures == null) {
      this.typeNomenclatures = this.getTypeNomenclatures();
    }
  }

  async getCategories() {
    return await this.http.get(this.authService.host + '/categories').toPromise();
  }

  async getArticles() {
    return await this.http.get(this.authService.host + '/articles').toPromise();
  }

  async getModels() {
    return await this.http.get(this.authService.host + '/models').toPromise();
  }

  async getNomenclatures() {
    return await this.http.get(this.authService.host + '/nomenclatures').toPromise();
  }

  async getTypeNomenclatures() {
    return await this.http.get(this.authService.host + '/type_nomenclatures').toPromise();
  }


  /*
  * |             |
  * | Categories  |
  * |             |
  * */
  getAllCategories() {
    return this.http.get(this.authService.host + '/categories');
  }

  addCategorie(categorie: Categorie) {
    return this.http.post(this.authService.host + '/categories', categorie);
  }

  getoneCategorie(url) {
    return this.http.get(this.authService.host + url);
  }

  updateCategorie(categorie, value) {
    return this.http.put(this.authService.host + '/categories/' + categorie.id, value);
  }

  deleteCategorie(categorie: Categorie) {
    return this.http.delete(this.authService.host + '/categories/' + categorie.id);
  }


  /*
  * |             |
  * | Articles    |
  * |             |
  * */
  getAllArticles(url) {
    return this.http.get(this.authService.host + url,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }

  getoneArticle(url) {
    return this.http.get(this.authService.host + url,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      }
    );
  }

  addArticle(article, user: User) {
    article.userId = user.id;
    return this.http.post(this.authService.host + '/api/addArticle',
      article,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }

  deleteArticle(article: Article) {
    return this.http.delete(this.authService.host + '/articles/' + article.id);
  }

  updateArticle(article) {
    return this.http.put(this.authService.host + '/articles/' + article.id, article,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }

  searchArticle(value) {
    return this.http.get(this.authService.host + '/api/searchArticle/' + value,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }


  /*
  * |             |
*   |   Models      |
  * |             |
  * */

  getAllModels(url) {
    return this.http.get(this.authService.host + url);
  }

  addNouveauModel(model: Model) {
    return this.http.post(this.authService.host + '/models', model,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }

  uploadImageModal(model: Model) {
    let formData: FormData = new FormData();
    formData.append('image', model.image);

    return this.http.post(this.authService.host + '/api/uploadImageModel/' + model.id, formData,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }

  deleteModel(model: Model) {
    return this.http.delete(this.authService.host + '/api/deleteModel/' + model.id,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }

  updateModel(model: Model) {
    return this.http.put(this.authService.host + '/models/' + model.id, model,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }

  /*
 * |             |
*   | Type  Nomenclature      |
 * |             |
 * */

  getTypeNomenclature(url) {
    return this.http.get(this.authService.host + url,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }

  postTypeNomenclature(url, typeNomenclature) {
    return this.http.post(this.authService.host + url, typeNomenclature,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }

  deleteTypeNomenclature(url) {
    return this.http.delete(this.authService.host + url,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }

  updateTypeNomenclature(url, typeNomenclature) {
    return this.http.put(this.authService.host + url, typeNomenclature,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }

  /*
* |             |
*   |  Nomenclature      |
* |             |
* */

  getNomenclature(url) {
    return this.http.get(this.authService.host + url,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }

  postNomenclature(url, nomenclature) {
    return this.http.post(this.authService.host + url, nomenclature,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }

  updateNomenclature(url, nomenclature) {
    return this.http.put(this.authService.host + url, nomenclature,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }


  uploadImageNomenclature(nomenclature: Nomenclature) {
    let formData: FormData = new FormData();
    formData.append('image', nomenclature.image);

    return this.http.post(this.authService.host + '/api/uploadImageNomenclature/' + nomenclature.id, formData,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }

  deleteNomenclature(nomenclature: Nomenclature) {
    return this.http.delete(this.authService.host + '/api/deleteNomenclature/' + nomenclature.id,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }

  deleteModelofNomenclatures(nomenclature, model) {
    let value = {
      'idmodel': model.id
    };
    return this.http.put(this.authService.host + '/api/deleteModelsOfNomenclature/' + nomenclature.id, value,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }
}
