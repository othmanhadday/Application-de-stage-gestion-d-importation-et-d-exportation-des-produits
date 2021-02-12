import {Component, OnInit} from '@angular/core';
import {AuthenticationService} from '../Services/authentication.service';
import {User} from '../model/User';
import {CompteService} from '../Services/compte.service';

@Component({
  selector: 'app-my-compte',
  templateUrl: './my-compte.component.html',
  styleUrls: ['./my-compte.component.css']
})
export class MyCompteComponent implements OnInit {
  public user = new User();
  error = {
    confirmpwd: null,
    password: null,
    changePwdSuccess: null
  };
  password;
  confirmPassword;
  showLoadingIndicator = false;

  constructor(public authService: AuthenticationService,
              private compteService: CompteService) {
  }

  async ngOnInit() {
    this.showLoadingIndicator = true;
    this.user = await this.authService.user;
    this.getImageFromServer(this.user);
    this.showLoadingIndicator = false;
  }


  getImageFromServer(user: User) {
    user.imageCin = [];
    user.imageDiplomes = [];
    // @ts-ignore
    for (let image of user.images) {
        // @ts-ignore
        let imageEachUser = image.name;
        if (imageEachUser.indexOf('personnel') !== -1) {
          user.imageUser = imageEachUser;
        }
        if (imageEachUser.indexOf('Cin') !== -1) {
          user['imageCin'].push(imageEachUser);
        }
        if (imageEachUser.indexOf('Diplomes') !== -1) {
          user['imageDiplomes'].push(imageEachUser);
        }
        this.showLoadingIndicator = false;
    }
  }


  onChangePassword() {
    this.showLoadingIndicator = true;
    if (this.validatonInputs()) {
      this.showLoadingIndicator = false;
      return;
    }
    this.compteService.changePwd(this.user, this.password)
      .subscribe(res => {
        this.error.changePwdSuccess = 'Pwd Change Successfully';
        setTimeout(() => {
          this.error.changePwdSuccess = null;
        }, 5000);
        this.password = null;
        this.confirmPassword = null;
        this.showLoadingIndicator = false;
      }, err => {
        console.log(err);
      });
  }

  validatonInputs() {
    this.showLoadingIndicator = true;
    let vide = false;
    if (this.password == undefined || this.password == '') {
      this.error.password = 'Password required !!!';
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      if (this.password.length < 6) {
        // @ts-ignore
        this.error.password = 'mot de pass seperieure de 5 lettres !!!';
        this.showLoadingIndicator = false;
        vide = true;
      } else {
        if (this.password != this.confirmPassword) {
          this.error.password = 'mot de passe non compatible !!!';
          this.error.confirmpwd = 'mot de passe non compatible !!!';
          this.showLoadingIndicator = false;
          vide = true;
        } else {
          this.error.password = null;
          this.error.confirmpwd = null;
        }
      }
    }

    if (this.confirmPassword == null || this.confirmPassword == '') {
      this.error.confirmpwd = 'Confirmation Password required !!!';
      // this.showLoadingIndicator = false;
      vide = true;
    } else {
      this.error.confirmpwd = null;
    }
    return vide;
  }
}
