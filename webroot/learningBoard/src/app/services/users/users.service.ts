import { Injectable } from '@angular/core';
import { CustomHttpsService } from '../configuration/custom-https.service';
import { AppSettings } from '../../app-settings';

@Injectable()
export class UsersService {

  constructor(
    public http: CustomHttpsService
  ) {

  }

  getUserDetails() {
    return this.http.get(AppSettings.API_ENDPOINT + 'users/me');
  }

  changePassword(userId, newPassword) {
    let data: any;
    data = {
      user_id : userId,
      new_password : newPassword
    }

    return this.http.post(AppSettings.API_ENDPOINT + 'users/updatePassword', data);
  }

}
