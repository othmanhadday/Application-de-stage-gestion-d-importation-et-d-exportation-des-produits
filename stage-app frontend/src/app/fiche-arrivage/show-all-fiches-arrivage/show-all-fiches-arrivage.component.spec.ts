import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ShowAllFichesArrivageComponent } from './show-all-fiches-arrivage.component';

describe('ShowAllFichesArrivageComponent', () => {
  let component: ShowAllFichesArrivageComponent;
  let fixture: ComponentFixture<ShowAllFichesArrivageComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ShowAllFichesArrivageComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ShowAllFichesArrivageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
