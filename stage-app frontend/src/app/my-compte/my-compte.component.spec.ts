import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MyCompteComponent } from './my-compte.component';

describe('MyCompteComponent', () => {
  let component: MyCompteComponent;
  let fixture: ComponentFixture<MyCompteComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MyCompteComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MyCompteComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
