import {AfterViewInit, Component, OnInit} from '@angular/core';
import {CompteService} from '../Services/compte.service';
import {AuthenticationService} from '../Services/authentication.service';
import * as $ from 'jquery';
import {User} from '../model/User';
import {Service} from '../model/Service';
import {Niveau} from '../model/Niveau';
import {HttpClient} from '@angular/common/http';
import {Router} from '@angular/router';
import {SSEService} from '../Services/sse.service';
import {stringify} from 'querystring';

@Component({
  selector: 'app-add-compte',
  templateUrl: './add-compte.component.html',
  styleUrls: ['./add-compte.component.css']
})
export class AddCompteComponent implements OnInit {
  showLoadingIndicator = false;
  public user = new User();
  services: Service[];
  niveaux: Niveau[];
  mode: number = 0;
  error = {
    file: null,
    email: null,
    cin: null,
    nom: null,
    service: null,
    niveau: null,
    password: null,
    serverError: null, tel: null,
    imageUser: null,
    imageCin: null,
    niveauScolaire: null,
    imaeDplomes: null,
    confirmpwd: null
  };
  AllUsers;
  fileisUpdated = {
    user: false,
    cin: false,
    diplomes: false
  };


  constructor(
    public authService: AuthenticationService,
    private compteService: CompteService,
    private router: Router,
    private sseService: SSEService
  ) {
  }

  ngOnInit(): void {
    this.showLoadingIndicator = true;
    this.getAllUser();

    const url = new URL('http://localhost:3000/.well-known/mercure');
    url.searchParams.append('topic', 'http://localhost:8000/ping');
    this.sseService.getServerSentEvent(url.toString())
      .subscribe(data => {
        console.log(data);
        this.getAllUser();
      });
  }

  getAllUser() {
    this.compteService.getAllUser().subscribe(
      resp => {
        this.AllUsers = resp['hydra:member'];
        console.log(this.AllUsers);
        if (this.AllUsers.length == 0) {
          this.authService.logout();
          this.router.navigateByUrl('login');
        }
        this.AllUsers.reverse();
        this.showLoadingIndicator = false;
      }, error => {
        console.log(error);
        this.showLoadingIndicator = false;
      }
    );
  }

  getImageFromServer(user:User) {
    // @ts-ignore
    for (let image of user.images) {
      this.compteService.getImageofEachUser(image).subscribe(res => {
        // @ts-ignore
        let imageEachUser = res.name;
        if (imageEachUser.indexOf('personnel') !== -1) {
          user.imageUser = imageEachUser;
        }
        if (imageEachUser.indexOf('Cin') !== -1) {
          user['imageCin'].push(imageEachUser);
        }
        if (imageEachUser.indexOf('Diplomes') !== -1) {
          user['imageDiplomes'].push(imageEachUser);
        }

      }, err => {
        console.log(err);
      });
    }
  }

  showUser(user: User) {
    user.imageDiplomes = [];
    user.imageCin = [];
    this.getImageFromServer(user);
    this.user = user;
    // @ts-ignore
    window.$('#myModel').modal('show');
  }

  showModeldeleteUser(user: any) {
    // @ts-ignore
    window.$('#myModelDelete').modal('show');
    this.user = user;
  }

  DeleteUserConfirmation() {
    this.showLoadingIndicator = true;
    this.compteService.deleteUser(this.user).subscribe(
      res => {
        console.log(res);
        this.showLoadingIndicator = true;
        // @ts-ignore
        window.$('#myModelDelete').modal('toggle');
        this.ngOnInit();
      }, err => {
        console.log(err);
      }
    );


  }

  showModelEditUser(user:User) {
    this.compteService.getServicesNiveaux().subscribe(
      resp => {
        // @ts-ignore
        this.services = resp.services as Service[];
        // @ts-ignore
        this.niveaux = resp.niveaux as Niveau[];
        // this.user.roles.service = this.services[0];
        // this.user.roles.niveau = this.niveaux[0];
        this.showLoadingIndicator = false;
      }, error => {
        console.log(error);
        this.mode = 2;
        this.showLoadingIndicator = false;
      });
    this.user = user;
    this.user.imageDiplomes = [];
    this.user.imageCin = [];
    this.fileisUpdated = {
      user: false,
      cin: false,
      diplomes: false
    };
    this.getImageFromServer(this.user);
    // @ts-ignore
    window.$('#myModelEdit').modal('show');
    console.log(this.user);
  }

  onEdit() {
    this.showLoadingIndicator = true;
    if (this.validationInputs(this.user) == true) {
      this.showLoadingIndicator = false;
      return;
    }

    this.compteService.editUser(this.user)
      .subscribe(res => {
        if (this.fileisUpdated.diplomes == true) {
          this.compteService.updateImagesDiplomes(this.user)
            .subscribe(res => {
              this.user = res as User;
            }, err => {
              console.log(err);
            });
        }
        if (this.fileisUpdated.user == true) {
          this.compteService.updateImageUser(this.user)
            .subscribe(res => {
              this.user = res as User;
            }, err => {
              console.log(err);
            });
        }
        if (this.fileisUpdated.cin == true) {
          this.compteService.updateImagesCin(this.user)
            .subscribe(res => {
              this.user = res as User;
            }, err => {
              console.log(err);
            });
        }

        // @ts-ignore
        window.$('#myModelEdit').modal('toggle');
        this.showLoadingIndicator = false;
        this.ngOnInit();
      }, err => {
        console.log(err);
      });
  }

  MAX_SIZE: number = 307200;

  onSelectedUserImage(event) {
    this.user.imageUser = null;
    if (event.target.files &&
      event.target.files.length > 0) {
      if (event.target.files[0].size < this.MAX_SIZE) {
        this.user.imageUser = event.target.files[0];
        this.error.imageUser = null;
        this.fileisUpdated.user = true;
      } else {
        this.fileisUpdated.user = false;
        this.error.imageUser = 'size est supérieure que 300kb';
      }
    } else {
      this.fileisUpdated.user = false;
      this.error.imageUser = 'select image';
    }
  }

  onSelectedUserCin(event) {
    let size = 0;
    this.user.imageCin = null;
    if (event.target.files &&
      event.target.files.length > 0) {
      for (let file of event.target.files) {
        size += file.size;
      }
      if (size < this.MAX_SIZE) {
        this.user.imageCin = event.target.files;
        this.error.imageCin = null;
        this.fileisUpdated.cin = true;
      } else {
        this.fileisUpdated.cin = false;
        this.error.imageCin = 'size est supérieure que 300kb';
      }
    } else {
      this.fileisUpdated.cin = false;
      this.error.imageCin = 'select image';
    }
  }

  onSelectedUserDiplomes(event) {
    let size = 0;
    this.user.imageDiplomes = null;
    if (event.target.files &&
      event.target.files.length > 0) {
      for (let file of event.target.files) {
        size += file.size;
      }
      if (size < this.MAX_SIZE) {
        this.user.imageDiplomes = event.target.files;
        this.error.imaeDplomes = null;
        this.fileisUpdated.diplomes = true;
      } else {
        this.error.imaeDplomes = 'size est supérieure que 300kb';
        this.fileisUpdated.diplomes = false;
      }
    } else {
      this.fileisUpdated.diplomes = false;
      this.error.imaeDplomes = 'select image';
    }
  }

  public validationInputs(user: User) {
    let vide: boolean = false;
    if (user.email == undefined || user.email == '') {
      this.error.email = 'Email required !!';
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      this.error.email = null;
    }

    if (user.cin == undefined || user.cin == '') {
      this.error.cin = 'Cin required !!';
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      this.error.cin = null;
    }

    if (user.fullName == undefined || user.fullName == '') {
      this.error.nom = 'Nom required !!';
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      this.error.nom = null;
    }

    if (user.tel == undefined || user.tel == '') {
      this.error.tel = 'Tel required !!';
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      this.error.tel = null;
    }

    if (user.niveauScolaire == undefined || user.niveauScolaire == '') {
      this.error.niveauScolaire = 'Niveau scolaire required !!';
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      this.error.niveauScolaire = null;
    }

    if (user.roles.service == undefined) {
      this.error.service = 'Service required !!';
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      this.error.serverError = null;
    }

    if (user.roles.niveau == undefined) {
      this.error.niveau = 'Niveau required !!';
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      this.error.niveau = null;
    }

    if (this.user.imageUser == null) {
      this.error.imageUser = 'select image';
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      this.error.imageUser = null;
    }

    if (this.user.imageCin == null) {
      this.error.imageCin = 'selectionner les images de votre carte identite';
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      this.error.imageCin = null;
    }

    if (this.user.imageDiplomes == null) {
      this.error.imaeDplomes = 'selectionner les images de votre Diplomes';
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      this.error.imaeDplomes = null;
    }

    if (user.password == undefined || user.password == '') {
      this.error.password = 'Password required !!!';
      console.log(this.error.password);
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      if (user.password.length < 6) {
        this.error.password = 'mot de pass seperieure de 5 lettres !!!';
        this.showLoadingIndicator = false;
        vide = true;
      } else {
        this.error.password = null;
      }
    }
    return vide;
  }

}
