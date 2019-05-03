import { TestBed, inject } from '@angular/core/testing';

import { PerformanceTaskService } from './performance-task.service';

describe('PerformanceTaskService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [PerformanceTaskService]
    });
  });

  it('should be created', inject([PerformanceTaskService], (service: PerformanceTaskService) => {
    expect(service).toBeTruthy();
  }));
});
