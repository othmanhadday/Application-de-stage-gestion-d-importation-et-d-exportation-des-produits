import {Injectable} from '@angular/core';
import {AuthenticationService} from './authentication.service';
import {HttpClient, HttpHeaders, HttpRequest} from '@angular/common/http';
import {User} from '../model/User';
import {host} from '@angular-devkit/build-angular/src/test-utils';
import {Router} from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class CompteService {

  constructor(
    private authService: AuthenticationService,
    private http: HttpClient,
    router:Router
  ) {
    if (authService.loginIsExpired() == true){
      authService.logout();
      router.navigate(['/login'],{queryParams : {message : "Votre session a été expire"}});
    }
  }

  public getServicesNiveaux() {
    return this.http.get(this.authService.host + '/roles');
  }


  getFormData(user) {
    let formData: FormData = new FormData();
    console.log(user);
    formData.append('cin', user.cin.toString());
    formData.append('email', user.email.toString());
    formData.append('fullName', user.fullName.toString());
    formData.append('password', user.password.toString());
    formData.append('niveauScolaire', user.niveauScolaire.toString());
    formData.append('imageUser', user.imageUser);
    formData.append('tel', user.tel.toString());
    for (const file of user.imageCin) {
      formData.append('imageCin[]', file);
    }
    for (const file of user.imageDiplomes) {
      formData.append('imageDiplomes[]', file);
    }
    formData.append('service', user.roles.service.id.toString());
    formData.append('niveau', user.roles.niveau.id.toString());
    return formData;
  }

  public postUser(user: User) {
    let formData: FormData = this.getFormData(user);

    return this.http.post(this.authService.host + '/register',
      formData
    );
  }

  public getAllUser() {
    return this.http.get(this.authService.host + '/users');
  }

  public getImageofEachUser(url: string) {
    return this.http.get(this.authService.host + url);
  }


  deleteUser(user) {
    return this.http.delete(this.authService.host + '/api/user/' + user.id + '/delete',
      {headers: new HttpHeaders({'Authorization': 'Bearer ' + this.authService.getToken()})}
    );
  }

  public editUser(user) {
    // let formData: FormData = this.getFormData(user);
    // console.log(formData);
    return this.http.put(this.authService.host + '/api/user/' + user.id + '/edit',
      user,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken(),
          'Content-Type': 'application/x-www-form-urlencoded'
        })
      }
    );
  }

  updateImageUser(user) {
    let formData: FormData = this.getFormData(user);
    formData.append('imageUser', user.imageUser);

    return this.http.post(this.authService.host + '/api/user/' + user.id + '/updateUserImage',
      formData,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      }
    );
  }

  updateImagesCin(user) {
    let formData: FormData = this.getFormData(user);
      formData.append('imageCin[]', user.imageCin);
    return this.http.post(this.authService.host + '/api/user/' + user.id + '/updateCinImages',
      formData,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      }
    );
  }

  updateImagesDiplomes(user) {
    let formData: FormData = this.getFormData(user);
    formData.append('imageDiplomes[]', user.imageDiplomes);
    return this.http.post(this.authService.host + '/api/user/' + user.id + '/updateDiplomesImages',
      formData,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      }
    );
  }

  changePwd(user:User,pwd){
    return this.http.post(this.authService.host + '/api/changePwd/' + user.id ,
      {'password':pwd},
      {headers: new HttpHeaders({'Authorization': 'Bearer ' + this.authService.getToken()})}
    );
  }

}
