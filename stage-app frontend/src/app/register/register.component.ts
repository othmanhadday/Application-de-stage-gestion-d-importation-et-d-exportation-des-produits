import {Component, OnInit} from '@angular/core';
import {Service} from '../model/Service';
import {Niveau} from '../model/Niveau';
import {CompteService} from '../Services/compte.service';
import {User} from '../model/User';
import {SSEService} from '../Services/sse.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent implements OnInit {
  showLoadingIndicator: any;
  mode: number;
  public user = new User();
  services: any;
  niveaux: any;
  error = {
    file: null,
    email: null,
    cin: null,
    nom: null,
    service: null,
    niveau: null,
    password: null,
    serverError: null,
    serverSuccess: null,
    tel: null,
    niveauScolaire: null,
    imageUser: null,
    imageCin: null,
    imaeDplomes: null,
    confirmpwd: null
  };
  Confirmpassword = null;




  constructor(private compteService: CompteService,
              private sseService: SSEService) {
  }

  ngOnInit(): void {
    this.showLoadingIndicator = true;
    this.compteService.getServicesNiveaux().subscribe(
      resp => {
        // @ts-ignore
        this.services = resp.services as Service[];
        // @ts-ignore
        this.niveaux = resp.niveaux as Niveau[];
        this.user.roles.service = this.services[0];
        this.user.roles.niveau = this.niveaux[0];
        this.showLoadingIndicator = false;
      }, error => {
        console.log(error);
        this.mode = 2;
        this.showLoadingIndicator = false;
      });
  }

  onRegister() {
    console.log(this.user)
    this.showLoadingIndicator = true;
    if (this.validationInputs(this.user) == true) {
      this.showLoadingIndicator = false;
      return;
    }
    this.compteService.postUser(this.user).subscribe(res => {
      this.showLoadingIndicator = false;
      // @ts-ignore
      if (res.success) {
        this.error.serverSuccess = 'Compte bien enregistrer';
        setTimeout(()=>{
          this.error.serverSuccess= null;
        },3000);

        this.user = new User();
        this.Confirmpassword =""
        this.ngOnInit();
      }
      // @ts-ignore
      if (res.error) {
        // @ts-ignore
        this.error.serverError = res.error;
      }
    }, error => {
      console.log(error);
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
      } else {
        this.error.imageUser = 'size est supérieure que 300kb';
      }
    } else {
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

      } else {
        this.error.imageCin = 'size est supérieure que 300kb';
      }
    } else {
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
      } else {
        this.error.imaeDplomes = 'size est supérieure que 300kb';
      }
    } else {
      this.error.imaeDplomes = 'select image';
    }
  }

  public validationInputs(user: User) {
    let vide: boolean = false;
    if (user.email == undefined || user.email == "") {
      this.error.email = 'Email required !!';
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      this.error.email = null;
    }

    if (user.cin == undefined || user.cin == "") {
      this.error.cin = 'Cin required !!';
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      this.error.cin = null;
    }

    if (user.fullName == undefined || user.fullName == "") {
      this.error.nom = 'Nom required !!';
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      this.error.nom = null;
    }

    if (user.tel == undefined || user.tel == "") {
      this.error.tel = 'Tel required !!';
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      this.error.tel = null;
    }

    if (user.niveauScolaire == undefined || user.niveauScolaire == "") {
      this.error.niveauScolaire = 'Niveau scolaire required !!';
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      this.error.niveauScolaire = null;
    }

    if (user.roles.service == undefined ) {
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

    if (user.password == undefined || user.password == "") {
      this.error.password = 'Password required !!!';
      console.log(this.error.password);
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      if (user.password.length < 6) {
        this.error.password = 'mot de pass seperieure de 5 lettres !!!';
        this.showLoadingIndicator = false;
        vide = true;
      }else {
        if (user.password != this.Confirmpassword) {
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

    if (this.Confirmpassword == null || this.Confirmpassword == "") {
      this.error.confirmpwd = 'Confirmation Password required !!!';
      this.showLoadingIndicator = false;
      vide = true;
    } else {
      this.error.confirmpwd = null;
    }
    return vide;
  }
}
