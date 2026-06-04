import argparse, json, sys, os, cv2
from ultralytics import YOLO

parser = argparse.ArgumentParser()
parser.add_argument('--weights',    required=True)
parser.add_argument('--source',     required=True)
parser.add_argument('--output-dir', required=True)
args = parser.parse_args()

model   = YOLO(args.weights)
results = model(args.source, verbose=False)
result  = results[0]

damage_type = None
confidence  = 0.0
count       = len(result.boxes)

if len(result.boxes) > 0:
    best       = result.boxes.conf.argmax()
    confidence = float(result.boxes.conf[best])
    damage_type = model.names[int(result.boxes.cls[best])].capitalize()  # 'Pothole' / 'Crack'

ai_name     = 'ai_' + os.path.basename(args.source)
output_path = os.path.join(args.output_dir, ai_name)
os.makedirs(args.output_dir, exist_ok=True)
cv2.imwrite(output_path, result.plot())

print(json.dumps({
    'detected':     damage_type is not None,
    'damage_type':  damage_type,
    'confidence':   confidence,
    'count':        count,
    'ai_image_name': ai_name
}))