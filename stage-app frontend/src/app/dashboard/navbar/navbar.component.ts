import {Component, OnInit} from '@angular/core';
import {AuthenticationService} from '../../Services/authentication.service';
import {Router} from '@angular/router';
import {SSEService} from '../../Services/sse.service';
import {NotifcationService} from '../../Services/notifcation.service';
import {CompteService} from '../../Services/compte.service';
import {Notification} from '../../model/Notification';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css']
})
export class NavbarComponent implements OnInit {
  countNbrNotification: number = 0;
  notifications: Array<Notification>;

  user;
  showLoadingIndicator = false;

  constructor(public authService: AuthenticationService,
              private notiService: NotifcationService,
              private compteService: CompteService,
              private router: Router,
              private sseService: SSEService) {
  }

  async ngOnInit() {
    this.showLoadingIndicator = true;
    let user = await this.authService.user;
    console.log(user);
    for (let image of user.images) {
      // @ts-ignore
      let imageEachUser = image.name;
      if (imageEachUser.indexOf('personnel') !== -1) {
        user.imageUser = await imageEachUser;
      }
    }
    this.user = user;

    this.getAllNot();
    if (!this.authService.loginIsExpired()) {
      const url = new URL('http://localhost:3000/.well-known/mercure');
      url.searchParams.append('topic', 'http://localhost:8000/notification');
      this.sseService.getServerSentEvent(url.toString())
        .subscribe(data => {
          console.log('notixcvbn,kldfgxhdfgfhgj');
          this.getAllNot();
        });
    }

    this.showLoadingIndicator = false;
  }

  async getAllNot() {
    this.countNbrNotification = 0;
    this.notifications = [];
    let notifications = await this.notiService.getAllNotification().toPromise();
    // @ts-ignore
    this.notifications = notifications;
    this.countNbrNotification = this.notifications.length;
    this.notifications.reverse();
  }


  onLogOut() {
    this.authService.logout();
    this.router.navigateByUrl('/login');
  }

  editSeenNotification(notification: any) {
    this.notiService.editSeenNotification(notification)
      .subscribe(res => {
        this.getAllNot();
        if (this.router.url !== '/dashboard' + notification.link) {
          this.router.navigate(['/dashboard' + notification.link]);
        } else {
          this.router.routeReuseStrategy.shouldReuseRoute = () => false;
          this.router.navigate(['/dashboard' + notification.link]);
        }
      }, error => {
        console.log(error);
      });
  }


  goToMyCompte() {
    this.router.navigate(['/dashboard/my-compte']);
  }
}
