import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {DashboardComponent} from './dashboard/dashboard.component';
import {AuthenticationComponent} from './authentication/authentication.component';
import {AddCompteComponent} from './add-compte/add-compte.component';
import {RegisterComponent} from './register/register.component';
import {MyCompteComponent} from './my-compte/my-compte.component';
import {ArticleComponent} from './product/article/article.component';
import {CategorieComponent} from './product/categorie/categorie.component';
import {ModelArticleComponent} from './product/model/model-article.component';
import {ArticleModelsComponent} from './product/article/article-models/article-models.component';
import {TypeNomenclatureComponent} from './product/type-nomenclature/type-nomenclature.component';
import {NomenclatureComponent} from './product/nomenclature/nomenclature.component';
import {FicheArrivageComponent} from './fiche-arrivage/add-new-fiche-arrivage/fiche-arrivage.component';
import {ShowAllFichesArrivageComponent} from './fiche-arrivage/show-all-fiches-arrivage/show-all-fiches-arrivage.component';
import {FicheArrivageMagasinierByIdComponent} from './fiche-arrivage/fiche-arrivage-magasinier-by-id/fiche-arrivage-magasinier-by-id.component';
import {FicheArrivageFinanceComponent} from './fiche-arrivage/fiche-arrivage-finance/fiche-arrivage-finance.component';
import {NotificationComponent} from './notification/notification.component';
import {FicheArrivageByIdComponent} from './fiche-arrivage/fiche-arrivage-by-id/fiche-arrivage-by-id.component';
import {FicheArrivageTransitaireComponent} from './fiche-arrivage/fiche-arrivage-transitaire/fiche-arrivage-transitaire.component';

const routes: Routes = [
  {path: 'login', component: AuthenticationComponent},
  {path: 'register', component: RegisterComponent},
  {
    path: 'dashboard',
    component: DashboardComponent,
    children: [
      {path: 'articles', component: ArticleComponent},
      {path: 'articles/:article', component: ArticleModelsComponent, runGuardsAndResolvers: 'always'},
      {path: 'categorie', component: CategorieComponent},
      {path: 'typeNomenclature', component: TypeNomenclatureComponent},
      {path: 'nomenclature', component: NomenclatureComponent},
      {path: 'models', component: ModelArticleComponent},
      {path: 'notification', component: NotificationComponent},
      {path: 'add-compte', component: AddCompteComponent},
      {path: 'my-compte', component: MyCompteComponent},
      {path: 'add-fiche-arrivage', component: FicheArrivageComponent},
      {path: 'show-fiches-arrivages', component: ShowAllFichesArrivageComponent},
      {path: 'fiche-arrivage/:id', component: FicheArrivageByIdComponent},
      {path: 'fiche-arrivage-finance/:id', component: FicheArrivageFinanceComponent},
      {path: 'fiche-arrivage-maga/:id', component: FicheArrivageMagasinierByIdComponent},
      {path: 'fiche-arrivage-Trans/:id', component: FicheArrivageTransitaireComponent},
    ]
  },
  {path: '', redirectTo: '/dashboard', pathMatch: 'full'}
];

@NgModule({
  imports: [RouterModule.forRoot(routes, {onSameUrlNavigation: 'reload'})],
  exports: [RouterModule]
})
export class AppRoutingModule {
}
