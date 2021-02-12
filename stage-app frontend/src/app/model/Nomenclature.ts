import {Model} from './Model';

export class Nomenclature {
  id: number;
  refIntern: string;
  nomFr: string;
  nomAr: string;
  nomEn: string;
  codeShort: string;
  designation: string;
  codeSage: string;
  image: string;
  typeNomenclature: any;
  models:Array<any>=[]
}
