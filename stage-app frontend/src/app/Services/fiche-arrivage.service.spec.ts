import { TestBed } from '@angular/core/testing';

import { FicheArrivageService } from './fiche-arrivage.service';

describe('FicheArrivageService', () => {
  let service: FicheArrivageService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(FicheArrivageService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
