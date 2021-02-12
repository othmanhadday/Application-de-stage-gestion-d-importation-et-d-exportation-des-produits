export class FicheArrivageMagasinier {
  id: number;
  dateArriveDepot: Date;
  verifierManitentionnaire: boolean;
  verifierMagasinier0: boolean;
  verifierMagasinier1: boolean;
  verifierMagasinier2: boolean;
  articleFicheArrivage: any;
  images: Array<any>;
  ficheArrivageMagasinierManutentionnaires: Array<any>;
  imagesOutSide: Array<any> = [];
  imagesInside: Array<any> = [];
  user: any;
}
