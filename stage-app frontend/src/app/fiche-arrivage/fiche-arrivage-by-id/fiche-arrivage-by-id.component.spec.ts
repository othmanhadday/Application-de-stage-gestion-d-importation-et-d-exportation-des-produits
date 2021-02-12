import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FicheArrivageByIdComponent } from './fiche-arrivage-by-id.component';

describe('FicheArrivageByIdComponent', () => {
  let component: FicheArrivageByIdComponent;
  let fixture: ComponentFixture<FicheArrivageByIdComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FicheArrivageByIdComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FicheArrivageByIdComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
