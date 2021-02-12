import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FicheArrivageMagasinierByIdComponent } from './fiche-arrivage-magasinier-by-id.component';

describe('FicheArrivageMagasinierByIdComponent', () => {
  let component: FicheArrivageMagasinierByIdComponent;
  let fixture: ComponentFixture<FicheArrivageMagasinierByIdComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FicheArrivageMagasinierByIdComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FicheArrivageMagasinierByIdComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
