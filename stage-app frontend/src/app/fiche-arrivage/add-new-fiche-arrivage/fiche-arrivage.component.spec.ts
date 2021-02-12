import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FicheArrivageComponent } from './fiche-arrivage.component';

describe('FicheArrivageComponent', () => {
  let component: FicheArrivageComponent;
  let fixture: ComponentFixture<FicheArrivageComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FicheArrivageComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FicheArrivageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
