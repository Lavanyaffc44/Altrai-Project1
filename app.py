import cv2
import pytesseract
import numpy as np
import io
from PIL import Image
from PIL import FPDF


# Open an image
img = Image.open("C:/Users/kalaiffc/OneDrive/Documents/lavanya project 1/.vscode/images/prescrption.4.jpg")#you image directory

# Display the image
img.show()

def ocr_core(img):
    text = pytesseract.image_to_string(img)
    return text
#convert textoutput to pdf
def text_to_pdf(text, pdf_file):
    pdf = FPDF()
    pdf.add_page()
    pdf.set_font("Arial", size=12)
    pdf.multi_cell(0, 10, text)
    pdf.output(pdf_file)

# Open the image using OpenCV
img_cv = cv2.imread('C:/Users/kalaiffc/OneDrive/Documents/lavanya project 1/.vscode/images/prescrption.4.jpg')#your image directory

# Convert to grayscale
gray_image = cv2.cvtColor(img_cv, cv2.COLOR_BGR2GRAY)

# Apply Gaussian blur
blurred_image = cv2.GaussianBlur(gray_image, (5, 5), 0)

# Apply thresholding
_, thresholded_image = cv2.threshold(blurred_image, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)

# Save the preprocessed image
cv2.imwrite("output.jpg", thresholded_image)

# Perform OCR on the preprocessed image
result = ocr_core(Image.fromarray(thresholded_image))

# Print the OCR result
print(result)

pdf_output = "output.pdf"  # Replace with your preferred PDF file name

print(f"Text extracted and saved to {pdf_output}")
