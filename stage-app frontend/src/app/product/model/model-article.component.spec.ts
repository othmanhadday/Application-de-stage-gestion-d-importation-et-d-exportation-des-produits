import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ModelArticleComponent } from './model-article.component';

describe('ModelArticleComponent', () => {
  let component: ModelArticleComponent;
  let fixture: ComponentFixture<ModelArticleComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ModelArticleComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ModelArticleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
