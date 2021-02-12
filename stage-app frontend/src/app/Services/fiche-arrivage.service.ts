import {Injectable} from '@angular/core';
import {AuthenticationService} from './authentication.service';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {FicheArrivageAchat} from '../model/FicheArrivageAchat';

@Injectable({
  providedIn: 'root'
})
export class FicheArrivageService {

  allFicheArrivage;
  currentFicheArrivage;
  depots = null;
  conteneurs;
  manutentonnaires = null;

  constructor(
    private authService: AuthenticationService,
    private http: HttpClient
  ) {
    if (this.allFicheArrivage == null) {
      this.allFicheArrivage = this.getAllFicheArrivage();
    }
    if (this.conteneurs == null) {
      this.conteneurs = this.getAllConteneurs();
    }
    if (this.depots == null) {
      this.depots = this.getAllDepots();
    }
    if (this.manutentonnaires == null) {
      this.manutentonnaires = this.getAllManutentionnaire();
    }
  }


  postData(url, obj) {
    return this.http.post(this.authService.host + url, obj,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }

  putData(url, obj) {
    return this.http.put(this.authService.host + url, obj,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }

  getData(url) {
    return this.http.get(this.authService.host + url,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        },)
      });
  }

  async getAllFicheArrivage() {
    return this.http.get(this.authService.host + '/fiche_arrivage_achats',
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      }).toPromise();
  }

  async getAllConteneurs() {
    return await this.http.get(this.authService.host + '/conteneurs',
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      }).toPromise();
  }

  async getAllDepots() {
    return await this.http.get(this.authService.host + '/depots',
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      }).toPromise();
  }

  async getAllManutentionnaire() {
    return await this.http.get(this.authService.host + '/api/getAllManu',
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      }).toPromise();
  }

  async getCurrentFicheArrivage(id) {
    this.currentFicheArrivage = await this.http.get(this.authService.host + '/fiche_arrivage_achats/' + id,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      }).toPromise();
  }


  deleteData(url) {
    return this.http.delete(this.authService.host + url,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }
}
