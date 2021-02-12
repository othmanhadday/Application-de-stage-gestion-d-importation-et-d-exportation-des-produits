import {Categorie} from './Categorie';

export class Article {
  id:number;
  name:string;
  refArticle:string;
  quantiteTotal:number;
  activteArticleSerAchat1=false;
  activteArticleSerAchat0=false;
  activteArticleSerTransitaire0=false;
  activteArticleSerInfo1=false;
  activteArticleSerInfo0=false;
  categorie:any;
  models:Array<any>;
  commentaires:Array<any>;
}
