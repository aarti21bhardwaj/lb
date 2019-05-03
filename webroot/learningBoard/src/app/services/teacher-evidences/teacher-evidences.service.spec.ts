import { TestBed, inject } from '@angular/core/testing';

import { TeacherEvidencesService } from './teacher-evidences.service';

describe('TeacherEvidencesService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [TeacherEvidencesService]
    });
  });

  it('should be created', inject([TeacherEvidencesService], (service: TeacherEvidencesService) => {
    expect(service).toBeTruthy();
  }));
});
