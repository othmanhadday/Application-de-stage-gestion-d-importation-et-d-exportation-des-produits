import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ArticleModelsComponent } from './article-models.component';

describe('ArticleModelsComponent', () => {
  let component: ArticleModelsComponent;
  let fixture: ComponentFixture<ArticleModelsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ArticleModelsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ArticleModelsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
