import {Component, OnInit} from '@angular/core';
import {AuthenticationService} from '../Services/authentication.service';
import {ActivatedRoute, Event, NavigationEnd, NavigationStart, Route, Router} from '@angular/router';

@Component({
  selector: 'app-authentication',
  templateUrl: './authentication.component.html',
  styleUrls: ['./authentication.component.css']
})
export class AuthenticationComponent implements OnInit {
  mode = 0;
  showLoadingIndicator = false;
  messageSessionExpire: null;

  constructor(private authService: AuthenticationService,
              private router: Router,
              private activatedRouter: ActivatedRoute) {
  }

  ngOnInit(): void {
    this.activatedRouter.queryParams.subscribe((param) => {
      this.messageSessionExpire = param['message'];
      setTimeout(() => {
        this.messageSessionExpire = null;
      }, 3000);
    });
    if (this.authService.isAuth()) {
      this.router.navigateByUrl('/dashboard');
    }
  }

  onLogin(value) {
    this.showLoadingIndicator = true;
    this.authService.login(value).subscribe(
      resp => {
        // @ts-ignore
        this.authService.saveToken(resp.token);
        this.router.navigateByUrl('/dashboard');
        this.authService.user = this.authService.getUser();
      }, error => {
        this.showLoadingIndicator = false;
        this.mode = 1;
      }
    );
  }


}
