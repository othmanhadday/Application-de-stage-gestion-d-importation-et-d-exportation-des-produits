import {FicheArrivageTransitaire} from './FicheArrivageTransitaire';
import {Dume} from './Dume';
import {FicheArrivageMagasinier} from './FicheArrivageMagasinier';
import {FicheArrivageFinance} from './FicheArrivageFinance';

export class ArticleFicheArrivageAchat {
  id: number;
  model: any;
  ficheArrivageAchat: any;
  conteneur: any;
  quantiteServAchat: number;
  quantiteServTransitaire: number;
  quantiteServMagasinier: number;
  verifierArticlesChaqueContenaireTransitaire: boolean;
  verifierArticlesChaqueContenaireMagasnier: boolean;
  dateOuvertConteneur: Date;
  images: Array<any> = [];
  imagesOutSide: Array<any> = [];
  imagesInside: Array<any> = [];
  dumes: Array<any> = [];
  ficheArrivageTransitaires: Array<any> = [];
  ficheArrivageMagasiniers: Array<FicheArrivageMagasinier> = [];
  ficheArrivageFinances: Array<FicheArrivageFinance> = [];

}
