import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FicheArrivageFinanceComponent } from './fiche-arrivage-finance.component';

describe('FicheArrivageFinanceComponent', () => {
  let component: FicheArrivageFinanceComponent;
  let fixture: ComponentFixture<FicheArrivageFinanceComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FicheArrivageFinanceComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FicheArrivageFinanceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
