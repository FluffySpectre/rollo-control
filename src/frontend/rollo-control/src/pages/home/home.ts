import { Component } from '@angular/core';
import { NavController, ToastController } from 'ionic-angular';
import { RolloService } from '../../providers/rollo-service';

@Component({
  selector: 'page-home',
  templateUrl: 'home.html',
  providers: [RolloService]
})
export class HomePage {
  constructor(private navCtrl: NavController, private toastCtrl: ToastController, private rolloService: RolloService) {}

  async onUpClick() {
    let success = await this.rolloService.rolloUp().catch(err => {});;
    if (success) {
      this.displayToast('Rollo wird geöffnet!');
    } else {
      this.displayToast('Es ist ein Fehler aufgetreten!');
    }
  }

  async onDownClick() {
    let success = await this.rolloService.rolloDown().catch(err => {});;
    if (success) {
      this.displayToast('Rollo wird geschlossen!');
    } else {
      this.displayToast('Es ist ein Fehler aufgetreten!');
    }
  }

  async onStopClick() {
    let success = await this.rolloService.rolloStop().catch(err => {});
    if (success) {
      this.displayToast('Rollo wurde gestoppt!');
    } else {
      this.displayToast('Es ist ein Fehler aufgetreten!');
    }
  }

  displayToast(msg, duration = 3000) {
    let toast = this.toastCtrl.create({
      message: msg,
      duration: duration,
      position: 'top'
    });
    toast.present();
  }
}
