import cv2
import pytesseract
from PIL import Image
from fpdf import FPDF
import os

# Function to perform OCR on an image
def ocr_core(img):
    text = pytesseract.image_to_string(img)
    return text

# Function to convert text to a PDF file
def text_to_pdf(text, pdf_file):
    pdf = FPDF()
    pdf.add_page()
    pdf.set_font("Arial", size=12)
    pdf.multi_cell(0, 10, text)
    pdf.output(pdf_file)

# Directory containing your images
image_dir = "C:/Users/kalaiffc/OneDrive/Documents/lavanya project 1/.vscode/images/"

# Directory to save PDFs
output_pdf_dir = "C:/Users/kalaiffc/OneDrive/Documents/lavanya project 1/.vscode/output_pdfs/"

# Create the output PDF directory if it doesn't exist
if not os.path.exists(output_pdf_dir):
    os.makedirs(output_pdf_dir)

# Process multiple images
for image_file in os.listdir(image_dir):
    if image_file.endswith(".jpg"):
        # Open the image using OpenCV
        img_cv = cv2.imread(os.path.join(image_dir, image_file))

        # Convert to grayscale
        gray_image = cv2.cvtColor(img_cv, cv2.COLOR_BGR2GRAY)

        # Apply Gaussian blur
        blurred_image = cv2.GaussianBlur(gray_image, (5, 5), 0)

        # Apply thresholding
        _, thresholded_image = cv2.threshold(blurred_image, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)

        # Perform OCR on the preprocessed image
        result = ocr_core(Image.fromarray(thresholded_image))

        # Save the extracted text to a PDF file
        pdf_output = os.path.join(output_pdf_dir, f"{os.path.splitext(image_file)[0]}.pdf")
        text_to_pdf(result, pdf_output)

        print(f"Text extracted from {image_file} and saved to {pdf_output}")
