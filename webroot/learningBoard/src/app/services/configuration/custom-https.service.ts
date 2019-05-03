import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpClientModule, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs/Observable';

const httpOptions = {
  headers: new HttpHeaders({ 'Content-Type': 'application/json', 'Accept': 'application/json' })
};

@Injectable()
export class CustomHttpsService {

  //  url = 'http://ts.twinspark.co/dev/isc_hamburg/api/';
  url = 'http://localhost/learningboard/api/';
  // url = 'http://ts.twinspark.co/dev/isk/api'

  constructor(private http: HttpClient) { }

  post(url, body, headers?: HttpHeaders) {
    headers = this.appendHeaders(headers);
    return this.http.post(url, body, {headers});
  }

  put(url, body, headers?: HttpHeaders) {
    headers = this.appendHeaders(headers);
    return this.http.put(url, body, {headers});
  }

  get(url, headers?: HttpHeaders) {
    headers = this.appendHeaders(headers);
    return this.http.get(url, {headers});
  }
  delete(url, headers?: HttpHeaders) {
    headers = this.appendHeaders(headers);
    return this.http.delete(url, {headers});
  }


  appendHeaders(params) {
    // if (typeof params === 'undefined' || params == null) {
    //   params = null
    // }
    const head = new HttpHeaders();
    const headers = head.set('Authorization',
    'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjIsImV4cCI6MTUyNjYxMDY3MX0.Bl544Evak7CcLgOl1hzd9LrDlFuOCtteWyrHytftPjI');
    // requestOptions.headers.append('Content-Type','application/json');
    // requestOptions.headers.append('Accept','application/json');
    return headers;
  }
}
