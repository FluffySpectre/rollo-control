import { Injectable } from '@angular/core';
import { Http } from '@angular/http';
import 'rxjs/Rx';

@Injectable()
export class RolloService {
  private apiUrl: string = 'http://192.168.0.12/rollo.php';

  constructor(private http: Http) {}

  async rolloUp(): Promise<boolean> {
    return new Promise<boolean>(async (resolve, reject) => {
      let response = await this.http.get(this.apiUrl + '?up=1')
                    .map(res => res.json())
                    .toPromise()
                    .catch(err => { reject(err); });
      if (response) {
        resolve(response.success);
      }
    });
  }

  async rolloDown(): Promise<boolean> {
    return new Promise<boolean>(async (resolve, reject) => {
      let response = await this.http.get(this.apiUrl + '?down=1')
                    .map(res => res.json())
                    .toPromise()
                    .catch(err => { reject(err); });
      if (response) {
        resolve(response.success);
      }
    });
  }

  async rolloStop(): Promise<boolean> {
    return new Promise<boolean>(async (resolve, reject) => {
      let response = await this.http.get(this.apiUrl + '?stop=1')
                    .map(res => res.json())
                    .toPromise()
                    .catch(err => { reject(err); });
      if (response) {
        resolve(response.success);
      }
    });
  }

  async rolloPosition(position: number): Promise<boolean> {
    return new Promise<boolean>(async (resolve, reject) => {
      let response = await this.http.get(this.apiUrl + '?position=' + position)
                    .map(res => res.json())
                    .toPromise()
                    .catch(err => { reject(err); });
      if (response) {
        resolve(response.success);
      }
    });
  }
}
