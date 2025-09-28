// Validación del formulario de contacto
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('contactForm');
  const submitBtn = document.getElementById('submitBtn');
  const btnText = document.querySelector('.btn-text');
  const btnLoading = document.querySelector('.btn-loading');
  const successMessage = document.getElementById('form-success');

  // Configurar fecha mínima para el campo de fecha
  const fechaInput = document.getElementById('fecha_inicio');
  const today = new Date().toISOString().split('T')[0];
  fechaInput.setAttribute('min', today);

  // Reglas de validación
  const validationRules = {
      nombre: {
          required: true,
          minLength: 2,
          pattern: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/,
          message: 'El nombre debe contener solo letras y tener al menos 2 caracteres'
      },
      email: {
          required: true,
          pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
          message: 'Por favor ingresa un email válido'
      },
      telefono: {
          required: true,
          pattern: /^(\+507\s?)?\d{4}-?\d{4}$/,
          message: 'Formato válido: +507 0000-0000 o 00000000'
      },
      servicio: {
          required: true,
          message: 'Por favor selecciona un servicio'
      },
      mensaje: {
          required: true,
          minLength: 20,
          maxLength: 1000,
          message: 'El mensaje debe tener entre 20 y 1000 caracteres'
      },
      politicas: {
          required: true,
          message: 'Debes aceptar los términos y condiciones'
      }
  };

  // Función para mostrar errores
  function showError(fieldName, message) {
      const errorElement = document.getElementById(`error-${fieldName}`);
      const fieldElement = document.getElementById(fieldName);
      
      if (errorElement) {
          errorElement.textContent = message;
          errorElement.style.display = 'block';
      }
      
      if (fieldElement) {
          fieldElement.style.borderColor = '#e74c3c';
      }
  }

  // Función para limpiar errores
  function clearError(fieldName) {
      const errorElement = document.getElementById(`error-${fieldName}`);
      const fieldElement = document.getElementById(fieldName);
      
      if (errorElement) {
          errorElement.style.display = 'none';
      }
      
      if (fieldElement) {
          fieldElement.style.borderColor = '#ddd';
      }
  }

  // Función para validar un campo específico
  function validateField(fieldName, value) {
      const rule = validationRules[fieldName];
      if (!rule) return true;

      // Validación requerido
      if (rule.required && (!value || value.trim() === '')) {
          showError(fieldName, `${fieldName === 'politicas' ? 'Este campo' : 'Este campo'} es requerido`);
          return false;
      }

      // Si el campo no es requerido y está vacío, no validar más
      if (!rule.required && (!value || value.trim() === '')) {
          clearError(fieldName);
          return true;
      }

      // Validación de longitud mínima
      if (rule.minLength && value.length < rule.minLength) {
          showError(fieldName, rule.message);
          return false;
      }

      // Validación de longitud máxima
      if (rule.maxLength && value.length > rule.maxLength) {
          showError(fieldName, rule.message);
          return false;
      }

      // Validación de patrón
      if (rule.pattern && !rule.pattern.test(value)) {
          showError(fieldName, rule.message);
          return false;
      }

      clearError(fieldName);
      return true;
  }

  // Función para validar checkbox
  function validateCheckbox(fieldName) {
      const checkbox = document.getElementById(fieldName);
      const rule = validationRules[fieldName];
      
      if (rule && rule.required && !checkbox.checked) {
          showError(fieldName, rule.message);
          return false;
      }
      
      clearError(fieldName);
      return true;
  }

  // Agregar validación en tiempo real a los campos
  Object.keys(validationRules).forEach(fieldName => {
      const field = document.getElementById(fieldName);
      if (field && field.type !== 'checkbox') {
          field.addEventListener('blur', function() {
              validateField(fieldName, this.value);
          });
          
          field.addEventListener('input', function() {
              if (this.value.trim() !== '') {
                  validateField(fieldName, this.value);
              }
          });
      } else if (field && field.type === 'checkbox') {
          field.addEventListener('change', function() {
              validateCheckbox(fieldName);
          });
      }
  });

  // Formatear automáticamente el teléfono
  const telefonoField = document.getElementById('telefono');
  telefonoField.addEventListener('input', function() {
      let value = this.value.replace(/\D/g, ''); // Remover caracteres no numéricos
      
      if (value.length <= 8) {
          // Formato local: 0000-0000
          if (value.length > 4) {
              value = value.substring(0, 4) + '-' + value.substring(4);
          }
      } else if (value.startsWith('507')) {
          // Formato internacional: +507 0000-0000
          let local = value.substring(3);
          if (local.length > 4) {
              local = local.substring(0, 4) + '-' + local.substring(4);
          }
          value = '+507 ' + local;
      }
      
      this.value = value;
  });

  // Contador de caracteres para el mensaje
  const mensajeField = document.getElementById('mensaje');
  const charCounter = document.createElement('div');
  charCounter.className = 'char-counter';
  charCounter.style.cssText = 'text-align: right; font-size: 0.8rem; color: #666; margin-top: 0.3rem;';
  mensajeField.parentNode.appendChild(charCounter);

  mensajeField.addEventListener('input', function() {
      const length = this.value.length;
      charCounter.textContent = `${length}/1000 caracteres`;
      
      if (length > 1000) {
          charCounter.style.color = '#e74c3c';
      } else if (length < 20) {
          charCounter.style.color = '#f39c12';
      } else {
          charCounter.style.color = '#27ae60';
      }
  });

  // Función para validar todo el formulario
  function validateForm() {
      let isValid = true;

      // Validar campos de texto
      Object.keys(validationRules).forEach(fieldName => {
          if (fieldName !== 'politicas') {
              const field = document.getElementById(fieldName);
              if (field) {
                  if (!validateField(fieldName, field.value)) {
                      isValid = false;
                  }
              }
          }
      });

      // Validar checkbox de políticas
      if (!validateCheckbox('politicas')) {
          isValid = false;
      }

      return isValid;
  }

  // Función para enviar formulario
  async function submitForm(formData) {
      try {
          // Simular envío (en un caso real, esto sería una llamada AJAX)
          const response = await fetch('procesar_contacto.php', {
              method: 'POST',
              body: formData
          });

          if (response.ok) {
              // Guardar datos en archivo de texto (simulado)
              const dataToSave = {
                  timestamp: new Date().toISOString(),
                  nombre: formData.get('nombre'),
                  email: formData.get('email'),
                  telefono: formData.get('telefono'),
                  empresa: formData.get('empresa'),
                  servicio: formData.get('servicio'),
                  presupuesto: formData.get('presupuesto'),
                  fecha_inicio: formData.get('fecha_inicio'),
                  mensaje: formData.get('mensaje'),
                  newsletter: formData.get('newsletter') || 'no'
              };

              // En un caso real, esto se enviaría al servidor
              console.log('Datos guardados:', dataToSave);
              
              // Mostrar mensaje de éxito
              form.style.display = 'none';
              successMessage.style.display = 'block';
              
              // Scroll al mensaje de éxito
              successMessage.scrollIntoView({ behavior: 'smooth' });
              
              return true;
          } else {
              throw new Error('Error en el servidor');
          }
      } catch (error) {
          console.error('Error al enviar formulario:', error);
          alert('Hubo un error al enviar el formulario. Por favor, inténtalo nuevamente.');
          return false;
      }
  }

  // Manejar envío del formulario
  form.addEventListener('submit', async function(e) {
      e.preventDefault();
      
      // Validar formulario
      if (!validateForm()) {
          // Hacer scroll al primer error
          const firstError = document.querySelector('.error-message[style*="block"]');
          if (firstError) {
              firstError.closest('.form-group').scrollIntoView({ 
                  behavior: 'smooth',
                  block: 'center'
              });
          }
          return;
      }

      // Deshabilitar botón y mostrar loading
      submitBtn.disabled = true;
      btnText.style.display = 'none';
      btnLoading.style.display = 'inline';

      // Preparar datos del formulario
      const formData = new FormData(form);
      
      // Intentar enviar formulario
      const success = await submitForm(formData);
      
      if (!success) {
          // Rehabilitar botón si hay error
          submitBtn.disabled = false;
          btnText.style.display = 'inline';
          btnLoading.style.display = 'none';
      }
  });

  // Función para generar archivo de datos (simulación)
  function generateDataFile(data) {
      const content = `
=== NUEVO CONTACTO ===
Fecha: ${data.timestamp}
Nombre: ${data.nombre}
Email: ${data.email}
Teléfono: ${data.telefono}
Empresa: ${data.empresa || 'No especificada'}
Servicio: ${data.servicio}
Presupuesto: ${data.presupuesto || 'No especificado'}
Fecha Inicio: ${data.fecha_inicio || 'No especificada'}
Newsletter: ${data.newsletter}
Mensaje: ${data.mensaje}
========================

`;
      
      // En un entorno real, esto se enviaría al servidor para guardar en archivo
      console.log('Contenido del archivo de datos:', content);
      
      // Simular guardado en localStorage para demostración
      const existingData = localStorage.getItem('contactos') || '';
      localStorage.setItem('contactos', existingData + content);
  }

  // Agregar animaciones a los elementos del formulario
  const formGroups = document.querySelectorAll('.form-group');
  formGroups.forEach((group, index) => {
      group.style.opacity = '0';
      group.style.transform = 'translateY(20px)';
      
      setTimeout(() => {
          group.style.transition = 'all 0.5s ease';
          group.style.opacity = '1';
          group.style.transform = 'translateY(0)';
      }, index * 100);
  });
});

// Función adicional para mostrar datos guardados (para demostración)
function mostrarContactosGuardados() {
  const datos = localStorage.getItem('contactos');
  if (datos) {
      console.log('Contactos guardados:', datos);
  } else {
      console.log('No hay contactos guardados');
  }
}

// Función para limpiar datos guardados (para demostración)
function limpiarContactosGuardados() {
  localStorage.removeItem('contactos');
  console.log('Contactos eliminados');
}