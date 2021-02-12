import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TypeNomenclatureComponent } from './type-nomenclature.component';

describe('TypeNomenclatureComponent', () => {
  let component: TypeNomenclatureComponent;
  let fixture: ComponentFixture<TypeNomenclatureComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TypeNomenclatureComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TypeNomenclatureComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
