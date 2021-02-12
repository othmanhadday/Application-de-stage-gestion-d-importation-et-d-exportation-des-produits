import {Commentaire} from './Commentaire';
import {ArticleFicheArrivageAchat} from './ArticleFicheArrivageAchat';

export class FicheArrivageAchat {
  id:number;
  activteArticleSerAchat1:boolean;
  activteArticleSerAchat0:boolean;
  activteArticleSerTransitaire0:boolean;
  activteArticleSerInfo1:boolean;
  activteArticleSerInfo0:boolean;
  user:any;
  depot:any;
  commentaires:Array<Commentaire>;
  articleFicheArrivageAchats:Array<ArticleFicheArrivageAchat>;
  finishOperation:boolean;
  createdAt=new Date();
  updatedAt=new Date();
}
