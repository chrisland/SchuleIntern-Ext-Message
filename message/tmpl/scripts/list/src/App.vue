<template>

  <div>

    <Spinner v-bind:loading="loading"></Spinner>
    <Error v-bind:error="error"></Error>

    <Modal v-bind:data="selectedItem"></Modal>

    <ul class="page-submenue">
      <li v-on:click="handlerOpenFolder('posteingang')" class="item">Posteingang</li>
      <li v-on:click="handlerOpenFolder('gesendet')" class="item">Gesendete</li>
      <li v-on:click="handlerOpenFolder('papierkorb')" class="item">Papierkorb</li>
      <li v-on:click="handlerOpenFolder('archiv')" class="item">Archiv</li>
      <li v-bind:key="index" v-for="(item, index) in  folders"  v-on:click="handlerOpenFolder('anderer')" class="item">
        <span>{{item.folderName}}</span>
      </li>
    </ul>

    <div class="box">
      <div class="box-body">

        <table class="si-table si-table-style-allLeft ">
          <thead>
          <tr>
            <td></td>
            <td></td>
            <td>Absender</td>
            <td>Betreff</td>
            <td>Anhänge</td>
            <td>Empfangen</td>
          </tr>
          </thead>
          <tbody>
          <tr v-bind:key="index" v-for="(item, index) in  messages" v-on:click="handlerOpenMessage(item)">
            <td>
              <i v-if="item.isRead == 1" class="fa fa-envelope-open"></i>
              <i v-else class="fa fa-envelope"></i>
            </td>
            <td>
              <i v-if="item.priority == 'HIGH'" class="fa fa-arrow-up text-red"></i>
              <i v-if="item.priority == 'LOW'" class="fa fa-arrow-down text-green"></i>
            </td>
            <td><User v-if="item.from" size="line" v-bind:data="item.from"  ></User></td>
            <td>{{item.subject}}</td>
            <td><i v-if="item.attachments" class="fa fa-file" title="Anhänge"></i></td>
            <td>{{item.time}}</td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>


  </div>
</template>

<script>

const axios = require('axios').default;

import User from './mixins/User.vue'
import Error from './mixins/Error.vue'
import Spinner from './mixins/Spinner.vue'
import Modal from './mixins/Modal.vue'

export default {
  components: {
    User, Error, Spinner, Modal
  },
  data() {
    return {
      apiURL: globals.apiURL,
      error: false,
      loading: false,

      folders: false, // from AJAX
      messages: false, // from AJAX
      message: false // from AJAX


    };
  },
  created: function () {

    this.loadFolders();

  },
  mounted() {
/*
    EventBus.$on('item-submit', data => {
      //console.log(data)

      const formData = new FormData();
      formData.append('id', data.id);
      formData.append('einheiten', data.einheiten);

      this.loading = true;
      var that = this;
      axios.post( this.apiURL+'/orderSlot', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        .then(function(response){
          if ( response.data ) {
            //that.list = response.data;
            //console.log(response.data.error);
            if (response.data.error == false) {
              that.loadList();
              that.selectedItem = false;
            } else {
              that.error = ''+response.data.msg;
            }
          } else {
            that.error = 'Fehler beim Laden. 01';
          }
        })
        .catch(function(){
          that.error = 'Fehler beim Laden. 02';
        })
        .finally(function () {
          // always executed
          that.loading = false;
        });

    });
*/
  },
  methods: {

    handlerOpenMessage: function (message) {

      console.log(message)
      if (!message.id) {
        return false
      }

      this.loading = true;
      var that = this;
      axios.get( this.apiURL+'/getMessage/'+message.id)
      .then(function(response){
        if ( response.data ) {
          if (!response.data.error) {
            that.message = response.data;
            //console.log(response.data);
          } else {
            that.error = ''+response.data.msg;
          }
        } else {
          that.error = 'Fehler beim Laden. 01';
        }
      })
      .catch(function(){
        that.error = 'Fehler beim Laden. 02';
      })
      .finally(function () {
        // always executed
        that.loading = false;
      });

    },
    handlerOpenFolder: function (folder) {

      if (!folder) {
        return false
      }

      this.loading = true;
      var that = this;
      axios.get( this.apiURL+'/getMessages/'+folder)
      .then(function(response){
        if ( response.data ) {
          if (!response.data.error) {
            that.messages = response.data;
            //console.log(response.data);
          } else {
            that.error = ''+response.data.msg;
          }
        } else {
          that.error = 'Fehler beim Laden. 01';
        }
      })
      .catch(function(){
        that.error = 'Fehler beim Laden. 02';
      })
      .finally(function () {
        // always executed
        that.loading = false;
      });


    },
    loadFolders: function () {

      this.loading = true;
      var that = this;
      axios.get( this.apiURL+'/getMyFolders')
      .then(function(response){
        if ( response.data ) {
          if (!response.data.error) {
            that.folders = response.data;
            //console.log(response.data);

          } else {
            that.error = ''+response.data.msg;
          }
        } else {
          that.error = 'Fehler beim Laden. 01';
        }
      })
      .catch(function(){
        that.error = 'Fehler beim Laden. 02';
      })
      .finally(function () {
        // always executed
        that.loading = false;
        if (that.folders) {
          that.handlerOpenFolder('posteingang');
        }

      });

    }


  }

};
</script>

<style>

</style>