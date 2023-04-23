<template>
    <div>
      <h2>Create Catalogue</h2>
      <form @submit.prevent="generatePdf" enctype="multipart/form-data">
        <label for="name">Item Name:</label>
        <input type="text" id="name" v-model="item.itemName" required>
        <br>
  
        <label for="price">Price:</label>
        <input type="number" id="price" v-model="item.itemPrice" required>
        <br>
  
        <label for="image">Image:</label>
        <input type="file" id="image" @change="handleImageUpload" required>
        <br>
  
        <button type="submit">Generate PDF</button>
      </form>
    </div>
  </template>
  
  <script>
  import jsPDF from "jspdf";
  import axios from "axios";
  export default {
    data() {
      return {
        item: {

          itemName: "",
          itemPrice: null,
          itemImage: null,
        }
      };
    },
    methods: {
      handleImageUpload(event) {
        this.item.itemImage = event.target.files[0];
      },
      generatePdf() {
        const doc = new jsPDF();
  
        doc.text(`Item Name: ${this.item.itemName}`, 20, 20);
        doc.text(`Price: ${this.item.itemPrice}`, 20, 30);
  
        const reader = new FileReader();
        reader.readAsDataURL(this.item.itemImage);
        reader.onload = () => {
          const imageDataUrl = reader.result;
          doc.addImage(imageDataUrl, "JPEG", 20, 40, 100, 100);
          
          axios.post('/store-form', {
            name: this.item.itemName,
            price: this.item.itemPrice,
            image: this.item.itemImage,
          },{
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          })
          .then(() => {
            console.log('Form data saved to database');
          })
          .catch((error) => {
            console.error(error.response.data);
          });
          
          
          doc.save("catalogue.pdf");
        };
      },
    },
  };
  </script>
  <style>
  body {
    font-family: Arial, sans-serif;
    font-size: 16px;
    line-height: 1.5;
  }

  label {
    display: inline-block;
    width: 100px;
    font-weight: bold;
    margin-bottom: 10px;
  }

  input[type="text"],
  input[type="number"] {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 10px;
  }

  input[type="file"] {
    margin-bottom: 10px;
  }

  button[type="submit"] {
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  button[type="submit"]:hover {
    background-color: #3e8e41;
  }

  h2 {
    margin-top: 0;
    font-size: 24px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
  }

  .error {
    color: red;
    font-weight: bold;
    margin-bottom: 10px;
  }
</style>
