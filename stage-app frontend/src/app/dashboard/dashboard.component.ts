import {Component, OnInit} from '@angular/core';
import {AuthenticationService} from '../Services/authentication.service';
import {Event, NavigationEnd, NavigationStart, Router} from '@angular/router';
import {FicheArrivageService} from '../Services/fiche-arrivage.service';
import {User} from '../model/User';
import {SSEService} from '../Services/sse.service';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent implements OnInit {

  showLoadingIndicator = true;

  constructor(public authService: AuthenticationService,
              private ficheArrivageService: FicheArrivageService,
              private sseService: SSEService,
              private router: Router) {

    if (this.authService.loginIsExpired() == true) {
      this.authService.logout();
      router.navigate(['/login'], {queryParams: {message: 'Votre session a été expire'}});
    }
  }

  async ngOnInit() {
    this.authService.user = await this.authService.user;
    if (!this.authService.isAuth()) {
      this.router.navigateByUrl('/login');
    }

    this.getAllAlert();

    const url = new URL('http://localhost:3000/.well-known/mercure');
    url.searchParams.append('topic', 'http://localhost:8000/alert');
    this.sseService.getServerSentEvent(url.toString())
      .subscribe(data => {
        this.getAllAlert();
      });
  }


  toggleSideBar() {
    // @ts-ignore
    window.$('#sidebar').toggleClass('active');
  }

  alerts;

  async getAllAlert() {
    await this.ficheArrivageService.getData('/alert_fche_arrivages').toPromise()
      .then(data => {
        this.alerts = null;
        this.alerts = data['hydra:member'];
        console.log(this.alerts);
      });
  }
}
