import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { AuthenticationComponent } from './authentication/authentication.component';
import { DashboardComponent } from './dashboard/dashboard.component';
import { NavbarComponent } from './dashboard/navbar/navbar.component';
import {HttpClient, HttpClientModule} from '@angular/common/http';
import {FormsModule} from '@angular/forms';
import { AddCompteComponent } from './add-compte/add-compte.component';
import { RegisterComponent } from './register/register.component';
import { MyCompteComponent } from './my-compte/my-compte.component';
import { CategorieComponent } from './product/categorie/categorie.component';
import { ArticleComponent } from './product/article/article.component';
import { ModelArticleComponent } from './product/model/model-article.component';
import {NgxPaginationModule} from 'ngx-pagination';
import {ArticleModelsComponent} from './product/article/article-models/article-models.component';
import { TypeNomenclatureComponent } from './product/type-nomenclature/type-nomenclature.component';
import {NomenclatureComponent} from './product/nomenclature/nomenclature.component';
import {FicheArrivageComponent} from './fiche-arrivage/add-new-fiche-arrivage/fiche-arrivage.component';
import { ShowAllFichesArrivageComponent } from './fiche-arrivage/show-all-fiches-arrivage/show-all-fiches-arrivage.component';
import { FicheArrivageMagasinierByIdComponent } from './fiche-arrivage/fiche-arrivage-magasinier-by-id/fiche-arrivage-magasinier-by-id.component';
import { FicheArrivageFinanceComponent } from './fiche-arrivage/fiche-arrivage-finance/fiche-arrivage-finance.component';
import { NotificationComponent } from './notification/notification.component';
import { FicheArrivageByIdComponent } from './fiche-arrivage/fiche-arrivage-by-id/fiche-arrivage-by-id.component';
import { FicheArrivageTransitaireComponent } from './fiche-arrivage/fiche-arrivage-transitaire/fiche-arrivage-transitaire.component';

@NgModule({
  declarations: [
    AppComponent,
    AuthenticationComponent,
    DashboardComponent,
    NavbarComponent,
    AddCompteComponent,
    RegisterComponent,
    MyCompteComponent,
    CategorieComponent,
    ArticleComponent,
    ModelArticleComponent,
    ArticleModelsComponent,
    TypeNomenclatureComponent,
    NomenclatureComponent,
    FicheArrivageComponent,
    ShowAllFichesArrivageComponent,
    FicheArrivageMagasinierByIdComponent,
    FicheArrivageFinanceComponent,
    NotificationComponent,
    FicheArrivageByIdComponent,
    FicheArrivageTransitaireComponent,
  ],
    imports: [
        BrowserModule,
        AppRoutingModule,
        HttpClientModule,
        FormsModule,
        NgxPaginationModule
    ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
