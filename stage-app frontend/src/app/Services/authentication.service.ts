import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {JwtHelperService} from '@auth0/angular-jwt';
import {Router} from '@angular/router';
import {User} from '../model/User';

@Injectable({
  providedIn: 'root'
})
export class AuthenticationService {

  host = 'https://127.0.0.1:8000';
  private jwtToken: string;
  public roles = {
    service: null,
    niveau: null
  };
  public username;
  user=null;


  constructor(private http: HttpClient,
              private router: Router) {
    this.user = this.getUser();
  }

  async getUser() {
    if (this.getToken()) {
      return await this.http.get(this.host + '/api/Currentuser',
        {
          headers: new HttpHeaders({
            'Authorization': 'Bearer ' + this.getToken()
          })
        }
      ).toPromise();
    }
  }

  login(user) {
    return this.http.post(this.host + '/api/login_check', user);
  }

  saveToken(jwt: string) {
    this.jwtToken = jwt;
    localStorage.setItem('token', jwt);
    this.getRoles();
  }

  getToken() {
    return localStorage.getItem('token');
  }

  getRoles() {
    let jwt = new JwtHelperService();
    this.roles = jwt.decodeToken(this.getToken()).roles;
  }

  isAuth() {
    if (this.getToken()) {
      return true;
    }
    return false;
  }

  loginIsExpired() {
    let jwt = new JwtHelperService();
    const isExpired = jwt.isTokenExpired(this.getToken());
    if (isExpired) {
      this.logout();
    }
    return isExpired;
  }

  logout() {
    this.jwtToken = null;
    this.roles = null;
    this.user = null;
    this.username = null;
    localStorage.removeItem('token');
  }

}
