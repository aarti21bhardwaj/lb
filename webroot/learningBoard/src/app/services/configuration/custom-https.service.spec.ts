import { TestBed, inject } from '@angular/core/testing';

import { CustomHttpsService } from './custom-https.service';

describe('CustomHttpsService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [CustomHttpsService]
    });
  });

  it('should be created', inject([CustomHttpsService], (service: CustomHttpsService) => {
    expect(service).toBeTruthy();
  }));
});
