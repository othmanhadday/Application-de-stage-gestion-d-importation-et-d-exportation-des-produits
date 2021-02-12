import {Injectable} from '@angular/core';
import {AuthenticationService} from './authentication.service';
import {HttpClient, HttpHeaders} from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class NotifcationService {

  constructor(private authService: AuthenticationService,
              private http: HttpClient
  ) {
  }

  getAllNotification() {
    return this.http.get(this.authService.host + '/api/notification',
      {headers: new HttpHeaders({'Authorization': 'Bearer ' + this.authService.getToken()})});
  }

  getNotification() {
    return this.http.get(this.authService.host + '/api/Allnotification',
      {headers: new HttpHeaders({'Authorization': 'Bearer ' + this.authService.getToken()})});
  }

  editSeenNotification(notification) {
    return this.http.put(this.authService.host + '/notifications/' + notification.id,
      {'seen': true});
  }
}
