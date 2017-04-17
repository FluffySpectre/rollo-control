import { Component } from '@angular/core';
import { NavController, ToastController } from 'ionic-angular';
import { RolloService } from '../../providers/rollo-service';

@Component({
  selector: 'page-about',
  templateUrl: 'about.html',
  providers: [RolloService]
})
export class AboutPage {
  constructor(private navCtrl: NavController, private toastCtrl: ToastController, private rolloService: RolloService) {}

  async onShutClick(position: number) {
    let success = await this.rolloService.rolloPosition(position).catch(err => {});
    if (success) {
      this.displayToast('Rollo ist in Position!');
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
