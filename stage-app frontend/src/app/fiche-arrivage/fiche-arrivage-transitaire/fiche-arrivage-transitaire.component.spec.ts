import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FicheArrivageTransitaireComponent } from './fiche-arrivage-transitaire.component';

describe('FicheArrivageTransitaireComponent', () => {
  let component: FicheArrivageTransitaireComponent;
  let fixture: ComponentFixture<FicheArrivageTransitaireComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FicheArrivageTransitaireComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FicheArrivageTransitaireComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
