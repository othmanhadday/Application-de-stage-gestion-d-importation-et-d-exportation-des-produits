import {Service} from './Service';
import {Niveau} from './Niveau';

export class User {
  public id: number;
  public fullName: String;
  public email: String;
  public cin: String;
  public password: String;
  public role:string;
  public roles = {
    service: new Service(),
    niveau: new Niveau()
  };
  niveauScolaire: any;
  tel: any;
  imageDiplomes:any;
  imageCin:any;
  imageUser:any;
  images:[];
  constructor() {  }
}
