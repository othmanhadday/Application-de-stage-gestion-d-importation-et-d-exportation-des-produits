import { Injectable } from '@angular/core';
import {AuthenticationService} from './authentication.service';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Commentaire} from '../model/Commentaire';

@Injectable({
  providedIn: 'root'
})
export class CommentaireService {

  constructor(
    private authService:AuthenticationService,
    private http:HttpClient
  ) { }

  addNewCommentaire(commentaire:Commentaire){
    return this.http.post(this.authService.host+"/api/addCommentaire",
      commentaire,
      {
        headers: new HttpHeaders({
          'Authorization': 'Bearer ' + this.authService.getToken()
        })
      });
  }
}
