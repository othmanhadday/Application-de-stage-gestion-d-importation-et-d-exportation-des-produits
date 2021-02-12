import {Component, OnInit} from '@angular/core';
import {AuthenticationService} from '../Services/authentication.service';
import {NotifcationService} from '../Services/notifcation.service';
import {Notification} from '../model/Notification';
import {Router} from '@angular/router';
import {SSEService} from '../Services/sse.service';
import {NavbarComponent} from '../dashboard/navbar/navbar.component';

@Component({
  selector: 'app-notification',
  templateUrl: './notification.component.html',
  styleUrls: ['./notification.component.css']
})
export class NotificationComponent implements OnInit {
  showLoadingIndicator = false;
  notifications: Array<Notification>;

  constructor(
    public authService: AuthenticationService,
    private router: Router,
    private notiService: NotifcationService,
    private sseService: SSEService
  ) {
  }

  async ngOnInit() {
    this.showLoadingIndicator = true;
    this.getAllNot();
    if (this.authService.loginIsExpired()) {
      const url = new URL('http://localhost:3000/.well-known/mercure');
      url.searchParams.append('topic', 'http://localhost:8000/notification');
      this.sseService.getServerSentEvent(url.toString())
        .subscribe(data => {
          this.getAllNot();
        });
    }
    this.showLoadingIndicator = false;
  }

  async getAllNot() {
    this.notifications = [];
    let notifications = await this.notiService.getNotification().toPromise();
    // @ts-ignore
    this.notifications = notifications as Array<Notification>;
    this.notifications.reverse();
  }

  editSeenNotification(notification: any) {
    console.log(notification);
    this.notiService.editSeenNotification(notification)
      .subscribe(res => {
        this.router.navigate(['/dashboard' + notification.link]);

      }, error => {
        console.log(error);
      });
  }
}
