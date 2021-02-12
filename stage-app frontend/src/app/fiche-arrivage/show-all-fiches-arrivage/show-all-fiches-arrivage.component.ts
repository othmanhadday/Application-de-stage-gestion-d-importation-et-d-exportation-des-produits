import {Component, OnInit} from '@angular/core';
import {FicheArrivageService} from '../../Services/fiche-arrivage.service';
import {Router} from '@angular/router';
import {AuthenticationService} from '../../Services/authentication.service';
import {User} from '../../model/User';

@Component({
  selector: 'app-show-all-fiches-arrivage',
  templateUrl: './show-all-fiches-arrivage.component.html',
  styleUrls: ['./show-all-fiches-arrivage.component.css']
})
export class ShowAllFichesArrivageComponent implements OnInit {
  showLoadingIndicator = false;
  fichesArrivages=[];

  constructor(
    public authService: AuthenticationService,
    private ficheArrivageService: FicheArrivageService,
    private router: Router
  ) {
  }

  async ngOnInit() {
    this.showLoadingIndicator = true;

    this.fichesArrivages = await this.ficheArrivageService.allFicheArrivage;
    this.fichesArrivages = this.fichesArrivages['hydra:member']
    console.log(this.fichesArrivages);
    this.showLoadingIndicator = false;


  }


  async ficheArrivageDetail(fiche) {
    if (this.authService.user.role.service.name == 'Service Finance') {
      this.router.navigateByUrl('dashboard/fiche-arrivage-finance/' + fiche.id);
    } else {
      this.router.navigateByUrl('dashboard/fiche-arrivage/' + fiche.id);
    }
  }
}
